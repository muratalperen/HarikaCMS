function freqCalc(yazi) {
	var aStopWords = new Array (
		"a",
"acaba",
"altı",
"ama",
"ancak",
"artık",
"asla",
"aslında",
"az",
"b",
"bana",
"bazen",
"bazı",
"bazıları",
"bazısı",
"belki",
"ben",
"beni",
"benim",
"beş",
"bile",
"bir",
"birçoğu",
"birçok",
"birçokları",
"biri",
"birisi",
"birkaç",
"birkaçı",
"birşey",
"birşeyi",
"biz",
"bize",
"bizi",
"bizim",
"böyle",
"böylece",
"bu",
"buna",
"bunda",
"bundan",
"bunu",
"bunun",
"burada",
"bütün",
"c",
"ç",
"çoğu",
"çoğuna",
"çoğunu",
"çok",
"çünkü",
"d",
"da",
"daha",
"de",
"değil",
"demek",
"diğer",
"diğeri",
"diğerleri",
"diye",
"dokuz",
"dolayı",
"dört",
"e",
"elbette",
"en",
"f",
"fakat",
"falan",
"felan",
"filan",
"g",
"gene",
"gibi",
"ğ",
"h",
"hâlâ",
"hangi",
"hangisi",
"hani",
"hatta",
"hem",
"henüz",
"hep",
"hepsi",
"hepsine",
"hepsini",
"her",
"her biri",
"herkes",
"herkese",
"herkesi",
"hiç",
"hiç kimse",
"hiçbiri",
"hiçbirine",
"hiçbirini",
"ı",
"i",
"için",
"içinde",
"iki",
"ile",
"ise",
"işte",
"j",
"k",
"kaç",
"kadar",
"kendi",
"kendine",
"kendini",
"ki",
"kim",
"kime",
"kimi",
"kimin",
"kimisi",
"l",
"m",
"madem",
"mı",
"mı",
"mi",
"mu",
"mu",
"mü",
"mü",
"n",
"nasıl",
"ne",
"ne kadar",
"ne zaman",
"neden",
"nedir",
"nerde",
"nerede",
"nereden",
"nereye",
"nesi",
"neyse",
"niçin",
"niye",
"o",
"on",
"ona",
"ondan",
"onlar",
"onlara",
"onlardan",
"onların",
"onların",
"onu",
"onun",
"orada",
"oysa",
"oysaki",
"ö",
"öbürü",
"ön",
"önce",
"ötürü",
"öyle",
"p",
"r",
"rağmen",
"s",
"sana",
"sekiz",
"sen",
"senden",
"seni",
"senin",
"siz",
"sizden",
"size",
"sizi",
"sizin",
"son",
"sonra",
"ş",
"şayet",
"şey",
"şeyden",
"şeye",
"şeyi",
"şeyler",
"şimdi",
"şöyle",
"şu",
"şuna",
"şunda",
"şundan",
"şunlar",
"şunu",
"şunun",
"t",
"tabi",
"tamam",
"tüm",
"tümü",
"u",
"ü",
"üç",
"üzere",
"v",
"var",
"ve",
"veya",
"veyahut",
"y",
"ya",
"ya da",
"yani",
"yedi",
"yerine",
"yine",
"yoksa",
"z",
"zaten",
"zira"
	);
	var aWords = new Array ();
	var aKeywords = new Array ();

	// the text
	var sText = yazi;

	// total character count
	var iCharCount = sText.length;

	// remove line breaks
	sText = sText.replace(/\s/g, ' ');

	// convert to lowercase
	sText = sText.toLowerCase();

	// remove peculiars
	sText = sText.replace(/[^a-zA-Z0-9äöüß]/g, ' ');

	// total word count
	aWords = sText.split(" ");
	iWordCount = aWords.length;
	var iCharCountWithout = 0;

	// count words
	for (var x = 0; x < aWords.length; x++) {
		iCharCountWithout += aWords[x].length;
	}

	aWords = new Array();

	// remove stop words
	for (var m = 0; m < aStopWords.length; m++) {
		sText = sText.replace(' ' + aStopWords[m] + ' ', ' ');
	}

	// explode to array of words
	aWords = sText.split(" ");

	// every word
	for (var x = 0; x < aWords.length; x++) {
		// trim the word
		var s = aWords[x].replace (/^\s+/, '').replace (/\s+$/, '');

		// if already in array
		if (aKeywords[s] != undefined) {
			// then increase count of this word
			aKeywords[s]++;
		}

		// if not counted yet
		else {
			if (s != '') {
				aKeywords[s] = 1;
			}
		}
	}

	// result
	sAlert = "Found keywords:";

	n = 1;
	ret = [];
	for (var sKey in aKeywords) {
		iNumber = aKeywords[sKey];
		fQuotient = Math.round(100 * (iNumber / iWordCount), 2);
		ret[n-1] = [iNumber, fQuotient, sKey];
		sAlert = sAlert + "\n" + iNumber + " times (" + fQuotient + " %): " + sKey;
		n++;

		// İlk 10 sonuç
		if (n > 10)
			break;
	}

	return ret;
}

var spamDiyeUyarildi = false;
function freqHesapla() {
	var icerikTextarea = document.getElementById('icerik');
	var monitorArea = document.getElementById('yaziFrequency');
	var keywordList = freqCalc(icerikTextarea.value);
	var keys = "";
	var spamVar = false;
	for (var i = 0; i < keywordList.length; i++) {
		spamVar = (spamVar) ? true : (keywordList[i][1] > 8);
		keys = keys + "<label class='label label-primary' style='margin:2px;'>" + keywordList[i][1] + "% " + keywordList[i][2] + "</label><br>";
	}
	keys = keys + "<hr><b>Toplam " + icerikTextarea.value.split(" ").length + " kelime</b>";
	monitorArea.innerHTML =  keys;
	if (spamVar && ! spamDiyeUyarildi) {
		bildirim("Anahtar Kelime Fazla Kullandınız", "%8'den fazla anahtar kelimenin kullanımı spam olarak algılanabilir. Yazınızı tekrar gözden geçirmeniz önerilir.", "w");
		spamDiyeUyarildi = true;
	}
}

function seo() {
	this.freqHesapla = freqHesapla;
}
