<?php 

namespace Focus599Dev\CrawlerRJ;

use DOMDocument;
use DomXpath;

class Crawler{

	protected $urls = array(
		'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/br/gov/rj/sef/gct/web/emitirdocumentoarrecadacao/DocumentoArrecadacaoController.jpf',
		'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/br/gov/rj/sef/gct/web/emitirdocumentoarrecadacao/loadFormLimpo.do',
		'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/br/gov/rj/sef/gct/web/emitirdocumentoarrecadacao/incluirDebito.do',
		'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/br/gov/rj/sef/gct/web/emitirdocumentoarrecadacao/transfereDadosDebitos.do'
	);

	protected $fase = 0;

	protected $text_html = '';

	protected $html;

	protected $data = array();

	protected $filePDF;

	protected $endFase = 3;

	public $imps = array();

	protected $header = array(
		// 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
		// 'Accept-Encoding: gzip, deflate',
		// 'Accept-Language: en-US,en;q=0.9,pt;q=0.8',
		// 'Cache-Control: no-cache',
		// 'Connection: keep-alive',
		// 'Host: www1.fazenda.rj.gov.br',
		// 'Pragma: no-cache',
		// 'Upgrade-Insecure-Requests: 1',
		// 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36'
	);

	function __construct($data){

		set_time_limit(0);

		error_reporting(1);

		ini_set("display_errors","On");

		$this->clearSessionCurl();

		$this->data = $data;

		if (session_status() == PHP_SESSION_NONE)
            session_start();
	}

	public function fase_0(){

		$html = $this->execCurl($this->urls[$this->fase], 'POST', null);

		$this->text_html = $html;
	}

	public function fase_1(){

		$data = array(
			'{pageFlow.documentoForm.naturezaDescricao}' => '',
			'{pageFlow.documentoForm.naturezaAlteracaoIncluir}' => '',
			'{pageFlow.documentoForm.listaCodificada}' => '',
			'hdnAterarDataPagamento' => '0',
			'{pageFlow.documentoForm.tipoPagamento}' => $this->data['wlw-select_key:{actionForm.tipoPagamento}'],
			'{pageFlow.documentoForm.descTipoPagamento}' => $this->data['{pageFlow.documentoForm.descTipoPagamento}'],
			'{pageFlow.documentoForm.icIdSessao}' => '',
			'exibirResumo' => '',
			'{pageFlow.documentoForm.valorTotalDocs}' => '',
			'{actionForm.quantidadeDocs}' => '0',
			'{actionForm.alterarItemLista}' => '0',
			'{pageFlow.documentoForm.alterarPorConsultar}' => '',
			'{actionForm.itemAlteracao}' => '',
			'wlw-select_key:{actionForm.tipoPagamento}OldValue' => '',
			'wlw-select_key:{actionForm.tipoPagamento}' => $this->data['wlw-select_key:{actionForm.tipoPagamento}'],
			'wlw-radio_button_group_key:{pageFlow.documentoForm.tipoDocumento}' => $this->data['wlw-radio_button_group_key:{pageFlow.documentoForm.tipoDocumento}'],
			'{pageFlow.documentoForm.dataPagamento}' => $this->data['{pageFlow.documentoForm.dataPagamento}'],
			'{actionForm.controleInclusao}' => $this->data['{actionForm.controleInclusao}']
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		$html = $this->execCurl($this->urls[$this->fase] . '?tipoPagamento=' . $this->data['wlw-select_key:{actionForm.tipoPagamento}'], 'POST', $data);

		$itemPag = $this->html->createElement('div');

		$tmpDoc = new DOMDocument();

		$tmpDoc->loadHTML($html);

		foreach ($tmpDoc->getElementsByTagName('fieldset')->item(0)->childNodes as $node) {
		    
		    $node = $this->html->importNode($node, true);
		    
		    $itemPag->appendChild($node);
		}

		foreach ($tmpDoc->getElementsByTagName('fieldset')->item(1)->childNodes as $node) {
		    
		    $node = $this->html->importNode($node, true);
		    
		    $itemPag->appendChild($node);
		}

		$node = $this->html->getElementById('resultFormulario');

		$node->appendChild($itemPag);

		$this->text_html = $this->html->saveHTML();

	}

	public function fase_2(){

		$data = array(
			'{pageFlow.documentoForm.naturezaDescricao}' => '',
			'{pageFlow.documentoForm.naturezaAlteracaoIncluir}' => '',
			'{pageFlow.documentoForm.listaCodificada}' => '',
			'hdnAterarDataPagamento' => '',
			'{pageFlow.documentoForm.tipoPagamento}' => '',
			'{pageFlow.documentoForm.descTipoPagamento}' => '',
			'{pageFlow.documentoForm.icIdSessao}' => '',
			'exibirResumo' => '1',
			'{pageFlow.documentoForm.valorTotalDocs}' => '',
			'{actionForm.quantidadeDocs}' => '0',
			'{actionForm.alterarItemLista}' => '0',
			'{pageFlow.documentoForm.alterarPorConsultar}' => '',
			'{actionForm.itemAlteracao}' => '',
			'wlw-select_key:{actionForm.tipoPagamento}OldValue' => '',
			'wlw-select_key:{actionForm.tipoPagamento}' => 1,
			'wlw-radio_button_group_key:{pageFlow.documentoForm.tipoDocumento}' => 'GNRE',
			'{pageFlow.documentoForm.dataPagamento}' => '' ,
			'wlw-select_key:{pageFlow.documentoForm.natureza}OldValue' => '',
			'wlw-select_key:{pageFlow.documentoForm.natureza}' => '' ,
			'wlw-select_key:{pageFlow.documentoForm.produtoNatureza}OldValue' => 'true',
			'wlw-select_key:{pageFlow.documentoForm.produtoNatureza}' => '',
			'{pageFlow.documentoForm.cnpjCpf}' => '',
			'{pageFlow.documentoForm.inscEstadual}' => '',
			'{pageFlow.documentoForm.nomeRazaoSocial}' => '',
			'{pageFlow.documentoForm.endereco}' => '',
			'wlw-select_key:{pageFlow.documentoForm.ufContribuinte}' => '',
			'{pageFlow.documentoForm.ufContribuinte}' => '',
			'{pageFlow.documentoForm.municipioContribuinte}' => '',
			'{pageFlow.documentoForm.cepContribuinte}' => '',
			'{pageFlow.documentoForm.dddContribuinte}' => '',
			'{pageFlow.documentoForm.telefoneContribuinte}' => '',
			'wlw-radio_button_group_key:{pageFlow.documentoForm.icPeriodoOperacao}' => 'porPeriodo',
			'wlw-select_key:{pageFlow.documentoForm.tipoPeriodo}OldValue' => '',
			'wlw-select_key:{pageFlow.documentoForm.tipoPeriodo}' => 'M',
			'{pageFlow.documentoForm.numeroNf}' => '',
			'{pageFlow.documentoForm.serieNf}' => '', 
			'wlw-select_key:{pageFlow.documentoForm.tipoNf}OldValue' => '',
			'wlw-select_key:{pageFlow.documentoForm.tipoNf}' => '0',
			'{pageFlow.documentoForm.dtEmissaoNf}' => '',
			'{pageFlow.documentoForm.cnpjCpfNf}' => '',
			'{pageFlow.documentoForm.justificativaIsento}' => '',
			'{pageFlow.documentoForm.valorAdicionalFrete}' => '', 
			'{pageFlow.documentoForm.valorOutrasDespesas}' => '', 
			'{pageFlow.documentoForm.especificacaoOutrasDesp}' => '',
			'{pageFlow.documentoForm.numDocumento}' => '',
			'{pageFlow.documentoForm.periodoReferencia}' => '',
			'{pageFlow.documentoForm.dataFatoGerador}' => '',
			'{pageFlow.documentoForm.diaVencimento}' => '',
			'{pageFlow.documentoForm.dataVencimento}' => '',
			'{pageFlow.documentoForm.tpDeclaracao}' => '',
			'{pageFlow.documentoForm.tpDeclaracaoCod}' => '',
			'{pageFlow.documentoForm.existeDI}' => '',
			'{pageFlow.documentoForm.dataInicioTermoResp}' => '',
			'{pageFlow.documentoForm.dataFimTermoResp}' => '',
			'{pageFlow.documentoForm.justificativa}' => '',
			'{pageFlow.documentoForm.icmsInformado}' => '',
			'{pageFlow.documentoForm.icmsCompensado}' => '',
			'{pageFlow.documentoForm.icmsAposCompensacao}' => '',
			'{pageFlow.documentoForm.fecpInformado}' => '',
			'{pageFlow.documentoForm.icmsAtualizado}' => '',
			'{pageFlow.documentoForm.moraIcms}' => '',
			'{pageFlow.documentoForm.valorMultaMoraICMS}' => '',
			'{pageFlow.documentoForm.totalIcms}' => '',
			'{pageFlow.documentoForm.fecpAtualizado}' => '',
			'{pageFlow.documentoForm.valorMoraFECP}' => '',
			'{pageFlow.documentoForm.valorMultaMoraFECP}' => '',
			'{pageFlow.documentoForm.totalFecp}' => '',
			'{actionForm.controleInclusao}' => '',
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		foreach ($this->data as $key => $value) {
			$data[$key] = $value;
		}

		$data['{pageFlow.documentoForm.tipoPagamento}'] = $data['wlw-select_key:{actionForm.tipoPagamento}'];

		$this->getImpostosICMS($data);

		$this->getImpostosFECP($data);

		$this->imps = $data;
		
		$html = $this->execCurl($this->urls[$this->fase], 'POST', $data, null, false);

		$this->text_html = $html;

	}

	public function fase_3(){

		$data = array(
			'{pageFlow.documentoForm.listaCodificada}' => '',
			'{pageFlow.documentoForm.tipoPagamento}' => '',
			'{pageFlow.documentoForm.tipoDocumento}' => '',
			'{pageFlow.documentoForm.cnpjCpf}' => '',
			'{pageFlow.documentoForm.icIdSessao}' => '',
			'{pageFlow.documentoForm.valorTotalDocs}' => '',
			'hdnQuantidadeDocs' => '',
			'{actionForm.itensSelecionados}' => '1',
			'{actionForm.tipoDocumento}' => '',
		);

		$this->html = new DOMDocument();

		$this->html->loadHTML($this->text_html);

		$data = $this->fillPost($data);

		$html = $this->execCurl($this->urls[$this->fase], 'POST', $data, null, false);

		if ($html != 'Invalid path /begin was requested'){
			
			if ($html){

				$this->savePDF($html);

			}

		}
	}

	private function getImpostosICMS(&$data){

		$urlICMS = 'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/dwr/exec/pfEmitirDocumento.calcularValorICMSQualif.dwr';

		$dataICMS = 'callCount=1' . chr(10);

		$dataICMS .= 'c0-scriptName=pfEmitirDocumento' . chr(10);
		
		$dataICMS .= 'c0-methodName=calcularValorICMSQualif' . chr(10);

		$dataICMS .= 'c0-id=' . (floor(rand() * 10001)) . '_' . time() . chr(10);

		$dataICMS .= 'c0-param0=' . 'string:' . urlencode($data['{pageFlow.documentoForm.icmsInformado}']) . chr(10);

		$dataICMS .= 'c0-param1=' . 'string:0%2C00' . chr(10);

		$dataICMS .= 'c0-param2=' . 'string:' .  urlencode($data['{pageFlow.documentoForm.dataPagamento}']) . chr(10);

		$dataICMS .= 'c0-param3=' . 'string:' .  urlencode($data['{pageFlow.documentoForm.dataVencimento}']) . chr(10);

		$dataICMS .= 'c0-param4=' . 'string:' . chr(10);

		$dataICMS .= 'c0-param5=' . 'number:0' . chr(10);

		$dataICMS .= 'xml=' . 'true';

		$response = $this->getCurlImpostos($urlICMS, $dataICMS);

		$dataImps = $this->parseImpostoICMS($response);

		$data = array_merge($data, $dataImps);
	}

	private function getImpostosFECP(&$data){

		$urlFECP = 'http://www1.fazenda.rj.gov.br/projetoGCTBradesco/dwr/exec/pfEmitirDocumento.calcularValorICMS.dwr';

		$dataFECP = 'callCount=1' . chr(10);

		$dataFECP .= 'c0-scriptName=pfEmitirDocumento' . chr(10);
		
		$dataFECP .= 'c0-methodName=calcularValorICMS' . chr(10);

		$dataFECP .= 'c0-id=' . (floor(rand() * 10001)) . '_' . time() . chr(10);

		$dataFECP .= 'c0-param0=' . 'string:' . urlencode($data['{pageFlow.documentoForm.fecpInformado}']) . chr(10);

		$dataFECP .= 'c0-param1=' . 'string:0%2C00' . chr(10);

		$dataFECP .= 'c0-param2=' . 'string:' .  urlencode($data['{pageFlow.documentoForm.dataPagamento}']) . chr(10);

		$dataFECP .= 'c0-param3=' . 'string:' .  urlencode($data['{pageFlow.documentoForm.dataVencimento}']) . chr(10);

		$dataFECP .= 'c0-param4=' . 'string:' . chr(10);

		$dataFECP .= 'xml=' . 'true';

		$response = $this->getCurlImpostos($urlFECP, $dataFECP);

		$dataImps = $this->parseImpostoFECP($response);

		$data = array_merge($data, $dataImps);
	}

	private function parseImpostoFECP($str){

		$explited = explode(';', $str);

		unset($explited[count($explited) -1]);

		$str_eval = '';

		foreach ($explited as $key => $code) {
			

			preg_match('/var /', $code, $match);

			if ($match){

				$str_eval .= str_replace('var ', '$', $code) . ';';

			}

		}

		$data = array();

		try{

			eval($str_eval);

			$data['{pageFlow.documentoForm.fecpAtualizado}'] = $s3;
			
			$data['{pageFlow.documentoForm.valorMoraFECP}'] = $s5;
			
			$data['{pageFlow.documentoForm.valorMultaMoraFECP}'] = $s6;

			$data['{pageFlow.documentoForm.totalFecp}'] = $s7;

		} catch(\Exception $e){

		}

		return $data;

	}

	private function parseImpostoICMS($str){

		$explited = explode(';', $str);

		unset($explited[count($explited) -1]);

		$str_eval = '';

		foreach ($explited as $key => $code) {
			
			preg_match('/var /', $code, $match);

			if ($match){

				$str_eval .= str_replace('var ', '$', $code) . ';';

			}

		}

		$data = array();

		try{

			eval($str_eval);

			$data['{pageFlow.documentoForm.icmsAtualizado}'] = $s3;
			
			$data['{pageFlow.documentoForm.moraIcms}'] = $s5;
			
			$data['{pageFlow.documentoForm.valorMultaMoraICMS}'] = $s6;

			$data['{pageFlow.documentoForm.totalIcms}'] = $s7;

			$data['{pageFlow.documentoForm.icmsCompensado}'] = $s2;

			$data['{pageFlow.documentoForm.icmsAposCompensacao}'] = $s4;

		} catch(\Exception $e){

		}

		return $data;

	}

	private function getCurlImpostos($url, $data){

		try{

			$ch = curl_init();
	
			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_POST, true);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);			

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type:text/plain',
                'Content-Length: ' . strlen($data))
            );

			$response = curl_exec($ch);

			curl_close($ch);

			return $response;

		} catch(\Exception $e){

			print_r('Erro: '. $e->getMessage());
		}
	}


	private function replaceImagesToBase64(){

		$linhaP = base64_encode(file_get_contents('images/2.gif'));

		$linhaB = base64_encode(file_get_contents('images/1.gif'));

		$logoES = base64_encode(file_get_contents('images/governo_peq.gif'));

		$tesoura = base64_encode(file_get_contents('images/tesoura2.gif'));

		$this->text_html = preg_replace('/\/imagens\/2.gif/', 'data:image/gif;base64,' . $linhaP , $this->text_html); 

		$this->text_html = preg_replace('/\/imagens\/1.gif/', 'data:image/gif;base64,' . $linhaB , $this->text_html); 
		
		$this->text_html = preg_replace('/\/imagens\/governo_peq.gif/', 'data:image/gif;base64,' . $logoES , $this->text_html); 

		$this->text_html = preg_replace('/\.\.\/imagens\/tesoura2.gif/', 'data:image/gif;base64,' . $tesoura , $this->text_html); 
		
		$this->text_html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $this->text_html);
		
		$this->text_html = preg_replace('#<html(.*?)>(.*?)<body(.*?)>#is', '', $this->text_html);

		$this->text_html = preg_replace('#</body(.*?)>(.*?)</html(.*?)>#is', '', $this->text_html);
		
		$this->text_html = preg_replace('/\n/', '', $this->text_html);
		
	}

	public function getBoleto(){

		try{
			
			$this->{"fase_" . $this->fase}();

			if ($this->endFase != $this->fase){
				
				$this->fase  = $this->fase + 1;

				$this->getBoleto();

			}

			return $this->isPDF();

		} catch (\Exception $e){

	        $this->logError($e->getMessage() . ' ' . $e->getLine());

			return false;

		}
	}


	private function execCurl($url, $method, $data, $certificado = null, $fallowLocation = true){
		
		$httpcode = null;

		$response = null;

		try{

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);

			if ($method == 'POST')
				curl_setopt($ch, CURLOPT_POST, true);

			if ($data)
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

			if ($fallowLocation)
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);

			curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
			
			curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt"); //saved cookies

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$response = curl_exec($ch);

			curl_close($ch);

		} catch (\Exception $e){

            throw $e; 
            
		}
		
		return $response;
	}

	private function logError($message){
		return file_put_contents(realpath(__DIR__ . '/../log') . '/log.txt', date('d/m/Y H:i:s') . ' ' . $message . PHP_EOL, FILE_APPEND);
	}

	private function fillPost ($post){
		
		$xpath = new DomXpath($this->html);

		foreach ($post as $key => $post_value) {

			foreach ($xpath->query('//input[@name="' . $key . '"]') as $rowNode) {

				if($rowNode->getAttribute('value') != '')
			    	$post[$key] = $rowNode->getAttribute('value');
			}
		}

		return $post;
	}
	
	private function savePDF($pdf){
		
		$file = $this->makeRandomString() . '.pdf';

		$folder = realpath(__DIR__ . '/../pdf') . '/';

		$this->filePDF = $folder . $file;	

		if ($pdf){
			return file_put_contents($folder . $file, $pdf);
		}

		return false;

	}

	private function makeRandomString($max=6) {
	    
	    $i = 0;
	    
	    $possible_keys = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    
	    $keys_length = strlen($possible_keys);
	    
	    $str = "";
	    
	    while( $i < $max) {
	        
	        $rand = mt_rand(1,$keys_length-1);
	        
	        $str.= $possible_keys[$rand];
	        
	        $i++;
	    }
	    
	    return $str;
	}

	private function clearSessionCurl(){
		unlink('cookie.txt');
	}

	public function isPDF(){
		return is_file($this->filePDF);
	}

	public function copyFilePDF($pathTo){

		try {

			if (is_file($this->filePDF)){
				
				copy($this->filePDF, $pathTo);

				unlink($this->filePDF);

				return $pathTo;

			}

			return false;

		} catch (\Exception $e){

			$this->logError($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());

			return false;
		}

		return false;

	}

	public function getImps(){
		return $this->imps;
	}
}

?>
