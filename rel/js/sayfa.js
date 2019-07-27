function bildirim(baslik, metin, durum = null){
	console.debug("asdf");
	var bildirimModal = document.getElementById("anaModal");
	bildirimModal.getElementsByClassName("modal-title")[0].innerHTML = baslik;
	bildirimModal.getElementsByClassName("modal-body")[0].innerHTML = metin;
	bildirimModal.className = "modal " + ((durum == null)?"":(durum == "s")?"modal-success":(durum == "w")?"modal-warning":(durum == "d")?"modal-danger":"modal-default") + " fade";
	$('#anaModal').modal();
}

function aboneOl(mailDiv, base_url) // Abone olma tuşunun ajaxla çalışmasını sağlar.
{
	var inputlar = mailDiv.getElementsByTagName('input');
	var mailDivIci = mailDiv.innerHTML;
	var isim = inputlar[0].value;
	var posta = inputlar[1].value;
	if (isim == "" || posta == ""){ // Boşlukları doldurmadan tuşa tıkladıysa
		bildirim("Tüm Boşlukları Doldurun", "Abone olmak için isminizi ve e-posta adresinizi yazmanız gerekir.", "w");
	} else {

		mailDiv.innerHTML = '<i class="fa fa-spin fa-refresh text-white"></i>';
		$.post(base_url + "api/mailAbone", {ad: isim, mail: posta}, function (gelen_cevap, gelen_stat){
			mailDiv.innerHTML = mailDivIci;
			if (gelen_stat == 404) {
				alert("404, Sayfa bulunamadı hatası. İnternet bağlantınızı kontrol edin.");
			}else if(gelen_cevap == null || gelen_cevap == "" || gelen_stat != "success"){
				alert("Bağlantı Hatası. İsteğe gelen_cevap gelmedi veya bir sunucu hatası. Tekrar deneyin.");
			}else if (gelen_cevap == "0" || gelen_cevap == "false") {
				alert("Sistemsel bir hata oluştu.");
			}else if (gelen_cevap == 1){
				mailDiv.innerHTML = '<h3 class="text-white">Başarılı</h3><span class="text-white"><i class="fa fa-check"></i> Artık Abonesiniz!</span>';
			}else{
				alert("Bilinmeyen bir hata oluştu. Bu sonucun çıkmaması gerekirdi: \n" + gelen_cevap);
			}
		});
	}
}
