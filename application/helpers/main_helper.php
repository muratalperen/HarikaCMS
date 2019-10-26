<?php

// DEBUG: Yönetici yoksa kurulum ekranına redirect etmesi gerekli ama veritabanına helperden bağlanılamıyor.

// Bu değişken kaldırılacak
define ('site_YOL', '');

/**
 * Ürün
 *
 * Ürünler'le ilgili temel bilgileri tutar.
 *
 * @var string	kategoriSayisi: Kategori Sayısı
 * @var string	kateg: Kategoriler
 * @var string	altkateg: Alt Kategoriler
 * @var string	Tkateg: Seflink halinde kategoriler
 * @var string	Taltkateg: Seflink halinde alt kategoriler
 * @method			urunLink: Verilen veritabanı sonucu veya kategori ve alt kategori id'si ile seflink'i verilen ürünün adresini verir
 */
class urun{
  public $kategoriSayisi = 1;
  public $kateg = ['Kategori Bir']; // WARNING: Burayı güncelleyince router'i de güncelle (array ve onun uzunluğu)
  public $altkateg = [['Al']];
	public $Tkateg = ['kategori-bir'];
  public $Taltkateg = [['al']];

	/**
	 * Ürün Link
	 *
	 * Verilen veritabanı sonucu veya kategori id, altkategori id ve sef
	 * ile ürünün bağlantısını döndürür
	 *
	 * @param int 		Kategori id veya veritabanı sonucu (Array)
	 * @param	int			Alt Kategori id
	 * @param	string	Sef adı
	 * @return string
	 */
	public function urunLink($kategori, $altkategori=0, $sef='')
	{
		// Veritabanı sonucu verildiyse
		if (gettype($kategori) == 'object') {
			return base_url() . $this->Tkateg[$kategori->kategori] . '/' . $this->Taltkateg[$kategori->kategori][$kategori->altkategori] . '/' . $kategori->sef;
		} else {
			return base_url() . $this->Tkateg[$kategori] . '/' . $this->Taltkateg[$kategori][$altkategori] . '/' . seflink($sef);
		}
	}
}


/**
 * Böyle Birşey Olamaz
 *
 * Gelen post verilerini işleyen sayfaya herhangi bir post verisi olmadan gelme
 * gibi, olamayacak veya yanlışlıkla olmuş durumlarda çalışarak; kullanıcıyı
 * verilen adrese gönderir
 *
 * @param	string	yönlendirilecek adres
 */
function boyle_birsey_olamaz($veGit='')
{
	$admindeMi = get_instance()->router->fetch_class() == 'admin' || get_instance()->router->fetch_class() == 'adminB';

	redirect(base_url(( ($admindeMi) ? 'admin/' : '' ) . $veGit));
}
// Bu fonksiyonun eski hali
function boyleBisiOlamaz($veGit='')
{
  boyle_birsey_olamaz($veGit);
}

/**
 * Render Page
 *
 * Verilen addaki view'i çalıştırır
 *
 * @param	string	View adı
 * @param	array 	Header bilgileri
 */
function render_page($viewName, $sayfaTitle, $ekHeader = array())
{
	$CI = get_instance();
	$onek = ($CI->router->fetch_class() == 'admin') ? 'admin/' : '' ;

	// Yönetici ise header'e yöneticinin adını ve id'sini verir
	if (isset($CI->session->get_userdata ()['admin'])) {
		$CI->headerInfo['adminInfo'] = $CI
		->db
		->where('id', $CI->session->get_userdata ()['admin'])
		->select('id,ad')
		->get('admin')
		->result()[0];
	}
	// Ek header verisi varsa, headere gönderir
	if ( ! empty($ekHeader)) {
		foreach ($ekHeader as $key => $value) {
			$CI->headerInfo[$key] = $value;
		}
	}

	$CI->headerInfo['meta']['baslik'] = $sayfaTitle;
	$CI->headerInfo['u'] = new Urun();
	$CI->viewData['u'] = new Urun();

	$CI->load->view($onek . 'include/header', $CI->headerInfo);
	$CI->load->view($onek . 'sayfa/' . $viewName, $CI->viewData);
	$CI->load->view($onek . 'include/footer');
}


/**
 * Yönetime Bildir
 *
 * Olmaması gereken durumları yönetime bildirir
 *
 * @param int 		Önem derecesi (0-12)
 * @param	string	Uyarının başlığı
 * @param	string	Uyarı
 * @return boolean
 */
function yonetime_bildir($onem, $baslik, $uyari)
{
	$bu = get_instance();
	$bu->load->model('AdminBildirim_model');
	if ($onem == 0) {
		return $bu->AdminBildirim_model->ekle($onem, $baslik, $uyari);
	} else {
		return TRUE;
	}
}
// Eski fonksiyon.
function yonetimeBildir($onem, $baslik, $uyari)
{
	return yonetime_bildir($onem, $baslik, $uyari);
}
// Eski fonksiyon.
function bildir($onem, $baslik, $uyari)
{
	return yonetime_bildir($onem, $baslik, $uyari);
}

/**
 * İs Bot
 *
 * Ziyaretçinin bot olup olmadığını kontrol eder.
 *
 * @return boolean
 */
function is_bot()
{
	return preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT']);
}

/**
 * Dosya Boyutu
 *
 * Verilen dizindeki dosyanın boyutunu kb, mb cinsinden verir
 *
 * @param	string	Dosyanın dizini
 * @return string
 */
function dosya_boyutu($dosyaDizin)
{
	$bayt = file_exists($dosyaDizin) ? filesize($dosyaDizin) : 0 ;
	// NOTE: GB kısmını yazmadım. Gerekmez diye düşünüyorum
	if ($bayt >= 1048576) {
		echo number_format($bayt / 1048576, 2) . ' MB';
	} elseif ($bayt >= 1024) {
		echo number_format($bayt / 1024, 2) . ' KB';
	} else {
		echo $bayt . ' bayt';
	}
}
function dosyaBoyut($dosyaDizin){
	return dosya_boyutu($dosyaDizin);
}


/**
 * Sef Link
 *
 * Verilen yazıyı url olarak kullanılabilir hale getirir
 *
 * @param string 	Url veya yazı
 * @param	array		Ayarlar
 * @return string
 */
function seflink($str, $options = array())
{
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
?>
