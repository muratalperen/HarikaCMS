// String.prototype.replaceAll = function(aranan, degisecek) {
// 	return this.replace(new RegExp(aranan, 'g'), degisecek);
// }

// FIXME: ajax formlarında 500 hatası gelirse hiç ses çıkartmıyorlar

function yuklenior(boxID, yap) {//Panele yükleniyor ekranı ekler, çıkarır
	document.getElementById(boxID).parentNode.getElementsByClassName('overlay')[0].style.display = yap ? "block" : "none";
}
function ajaxKontrol(cevap, stat) {
	$dondur = false;
	if (stat == 404) {
		bildirim("404 Hatası", "Sayfa bulunamadı hatası. İnternet bağlantınızı kontrol edin.", "w");
	}else if(cevap == null || cevap == "" || stat != "success"){
		bildirim("Bağlantı Hatası", "İsteğe cevap gelmedi veya bir sunucu hatası. Tekrar deneyin.", "d");
	}else if (cevap == "0" || cevap == "false") {
		bildirim("Bir Hata", "Sistemsel bir hata oluştu. Bu hata yerine kasıtlı engelleme de olabilir.", "d");
	}else{
		$dondur = true;
	}
	return $dondur;
}
function whereID(id) {//$tumAdminler'de id'si id olan array'ı döndürür
	for (var i = 0; i < mesaj.adminlerTablosu.length; i++) {
		if (mesaj.adminlerTablosu[i]["id"] == id) {
			return mesaj.adminlerTablosu[i];
		}
	}
}
function jsonCevir(cevirilecekYazi){
	var jsonuc = [];
	try{
		jsonuc = JSON.parse(cevirilecekYazi);
	}catch(hata){
		bildirim("Sunucu Hatası", "Sunucudan gelen veriler işlenemeyecek biçimde. Sorun devam ederse sayfayı yenileyebilir veya yöneticinizle iletişime geçebilirsiniz.", "w");
	}
	return jsonuc;
}

function mesajEkle (ataninID, msjciID, tarih, msjID, msj) {
	console.debug(ataninID);
	var msjEkle = '<div class="direct-chat-msg ' + ((ataninID == msjciID)?'':'right') + '" id="msjid'+ msjID +'">\
		<div class="direct-chat-info clearfix">\
			<span class="direct-chat-name pull-' + ((ataninID == msjciID)?'left':'right') + '">' + whereID(ataninID)["ad"] + '</span>\
			<span class="direct-chat-timestamp pull-' + ((ataninID == msjciID)?'right':'left') + '">' + tarih + '\
				&nbsp;&nbsp;<span class="direct-chat-timestamp"><a title="Bu mesajı sil" onclick="mesaj.sil(' + msjID  + ', ' + ataninID + ');"><i class="fa fa-trash"></i></a></span>\
			</span>\
		</div>\
		<img class="direct-chat-img" src="' + mesaj.base_url + 'rel/img/admin/' + ataninID + '.jpg" alt="Yöneticinin Resmi">\
		<div class="direct-chat-text">'+ msj +'</div>\
	</div>';
	document.getElementById(msjciID + "msj").innerHTML += msjEkle;
	document.getElementById(msjciID + "msj").scrollTo(0, document.getElementById(msjciID + "msj").scrollHeight);//Scroll aşağı indir
}

function mesajOncekileriYukle(admininID) {
	admininAD = whereID(admininID)['ad'];
	yuklenior(admininID + "msj", true);
	$.post(mesaj.base_url + 'adminB/mesaj', {Tal: admininID}, function (gelen_cevap, gelen_stat){

		if(ajaxKontrol(gelen_cevap, gelen_stat)) {
			document.getElementById(admininID + "msj").innerHTML = ""; // Kutuyu boşaltıyoruz
			var jsonuc = jsonCevir(gelen_cevap);
			for (var i = jsonuc.length-1; i >= 0; i--) {
				mesaj.ekle(jsonuc[i]["gonderenID"], ((jsonuc[i]["gonderenID"] == admininID)?jsonuc[i]["gonderenID"]:jsonuc[i]["alanID"]), jsonuc[i]["tarih"], jsonuc[i]["id"] ,jsonuc[i]["icerik"]);
			}
		}
		yuklenior(admininID + "msj", false);

	});
}

function mesajAc(admininID){
	admininAD = whereID(admininID)["ad"];
	admininMA = whereID(admininID)["mail"];
	if (document.getElementById(admininID + "msj") == null) {

		var yazilacakTaslak = mesaj.mesajTaslak;
		var msjBilgisi = [];
		msjBilgisi["__id__"] = admininID;
		msjBilgisi["__ad__"] = admininAD;
		msjBilgisi["__mail__"] = admininMA;
		for(mm in msjBilgisi){
			if (mm != "mesajlar") {
				yazilacakTaslak = yazilacakTaslak.replace(new RegExp(mm, 'g'), msjBilgisi[mm]);
			}
		}
		mesaj.mesajAlani.innerHTML += yazilacakTaslak;

		$.post(mesaj.base_url + 'adminB/mesaj', {al: admininID}, function (gelen_cevap, gelen_stat){
			if (ajaxKontrol(gelen_cevap, gelen_stat)) {
				var jsonuc = jsonCevir(gelen_cevap);
				if (jsonuc.length < 20) {//20'den az mesaj varsa, "önceki mesajları yükle" tuşu olmamalı
					document.getElementById(admininID + 'msj').getElementsByClassName('direct-chat-msg hepsiniYukle')[0].remove();
				}
				for (var i = jsonuc.length-1; i >= 0; i--) {
					mesaj.ekle(jsonuc[i]["gonderenID"], ((jsonuc[i]["gonderenID"] == admininID)?jsonuc[i]["gonderenID"]:jsonuc[i]["alanID"]), jsonuc[i]["tarih"], jsonuc[i]["id"] ,jsonuc[i]["icerik"]);
				}
				yuklenior(admininID + "msj", false);
				//Arkaplanda mesaj kontrolü
				window.setInterval(function(){
					$.post(mesaj.base_url + 'adminB/mesaj', {al: admininID, sadeceYeniler: true}, function (gelen_cevap, gelen_stat){
						if (ajaxKontrol(gelen_cevap, gelen_stat)) {
							var yeniMSJ = jsonCevir(gelen_cevap);
							if (yeniMSJ != null) {
								for (var i = 0; i < yeniMSJ.length; i++) {
									mesaj.ekle(yeniMSJ[i]["gonderenID"], ((yeniMSJ[i]["gonderenID"] == admininID)?yeniMSJ[i]["gonderenID"]:yeniMSJ[i]["alanID"]), yeniMSJ[i]["tarih"], yeniMSJ[i]["id"], yeniMSJ[i]["icerik"]);
								}
							}
						}
					});
				}, 4000);// 4 sn'de bir yeni mesaj kontrol et.
			}
		});
	}else{//Zaten açıksa bir daha açma
		window.location.href = "#" + admininID + "msj";
	}
}
function mesajAt(id, msjElem){
	// yuklenior(, true);
	msj = msjElem.value;
	msjElem.value = "";
	if (msj == "") {
		bildirim ("Bu Mümkün Değil!", "Boş mesaj atamazsınız!", "w");
	}else{
		$.post(mesaj.base_url + 'adminB/mesaj', {at: msj, atKime:id}, function (gelen_cevap, gelen_stat) {
			if (ajaxKontrol(gelen_cevap, gelen_stat)) {
				var yeniMSJ = jsonCevir(gelen_cevap);
				if (yeniMSJ != null) {
					mesaj.ekle(yeniMSJ["gonderenID"], ((yeniMSJ["gonderenID"] == id)?yeniMSJ["gonderenID"]:yeniMSJ["alanID"]), yeniMSJ["tarih"], yeniMSJ["id"], yeniMSJ["icerik"]);
				}else {
					bildirim("Bir sorun oluştu", "Mesajının gönderilebildiğinden emin değiliz. Sayfayı yenilemek bazen çözüm olabilir.", "d");
				}
			}
		});
	}
}

function mesajSil(mesajinID, msjKimin, herkesden = 0) {
	if (msjKimin == mesaj.suanID) {//Kendi mesajını silecek
		if (herkesden == 0) {//Kimden sileceğini belirtmemiş
			bildirim("Mesajı Şuradan Sil:",
				'<input type="button" value="Sadece Kendimden" onclick="mesaj.sil('+mesajinID+', '+msjKimin+', 2);" class="btn btn-warning">\
				 <input type="button" value="Herkesden" onclick="mesaj.sil('+mesajinID+', '+msjKimin+', 1);" class="btn btn-danger">'
			);
		} else {	//Kimden sileceğini belirtmiş (1 = herkesden sil)
			$.post(mesaj.base_url + 'adminB/mesaj', {sil: mesajinID, herkes: ((herkesden == 1)?1:0)}, function (gelen_cevap, gelen_stat) {
				if (ajaxKontrol(gelen_cevap, gelen_stat)){
					if (gelen_cevap == "1"){
						document.getElementById("msjid" + mesajinID).remove();
					}else {
						bildirim("Bir sorun oluştu", "Mesajının silindiğinden emin değiliz. Sayfayı yenilemek bazen çözüm olabilir.", "d");
					}
				}
			});
		}
	} else { // Başkasının gönderdiği mesajı silecek. Yani görmeyecek.
		$.post(mesaj.base_url + 'adminB/mesaj', {sil: mesajinID}, function (gelen_cevap, gelen_stat) {
			if (ajaxKontrol(gelen_cevap, gelen_stat)){
				if (gelen_cevap == "1"){
					document.getElementById("msjid" + mesajinID).remove();
				}else {
					bildirim("Bir sorun oluştu", "Mesajının silindiğinden emin değiliz. Sayfayı yenilemek bazen çözüm olabilir.", "d");
				}
			}
		});
	}
}

function mesajlarTemizle(kimle, eminmisin = false) {//Kimle ye verilen id'deki kişiyle olan mesajlaşmaları temizler
	if (eminmisin) {
		yuklenior(kimle + "msj", true);
		$.post(mesaj.base_url + 'adminB/mesaj', {temizle: kimle}, function (gelen_cevap, gelen_stat) {
			if (ajaxKontrol(gelen_cevap, gelen_stat)) {
				if (gelen_cevap == "2") {
					bildirim("Tam Silinemedi", "Mesajların tamamı silinemedi.", "w");
				} else if (gelen_cevap == "1"){
					document.getElementById(kimle + "msj").innerHTML = "";
				} else {
					bildirim("Bir sorun oluştu", "Mesajların silindiğinden emin değiliz. Sayfayı yenilemek bazen çözüm olabilir.", "d");
				}
			}
			yuklenior(kimle + "msj", false);
		});
	} else {
		bildirim("Tüm Mesajları Sil",
			'Tüm mesajlar silinsin mi? (Sadece sizden silinecekler) <input type="button" value="Tüm mesajları sil" onclick="mesaj.temizle('+ kimle +', true);" class="btn btn-danger">'
		);
	}
}

function mesajClass(suanID, suanAD, base_url, mesajAlani, mesajTaslak, adminlerTablosu) {//Mesaj Objesi
	this.suanAD = suanAD;
	this.suanID = suanID;
	this.mesajAlani = mesajAlani;
	this.mesajTaslak = mesajTaslak;
	this.base_url = base_url;
	this.adminlerTablosu = adminlerTablosu;

	this.ekle = mesajEkle;
	this.goster = mesajAc;
	this.gonder = mesajAt;
	this.sil = mesajSil;
	this.temizle = mesajlarTemizle;
	this.hepsiniYukle = mesajOncekileriYukle;
}
