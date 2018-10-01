<?php 

include ('crawler.php');

include ('vendor/autoload.php');		

$data = array(
	'{pageFlow.documentoForm.descTipoPagamento}' => 'ICMS/FECP',
	'{pageFlow.documentoForm.naturezaDescricao}' => 'ICMS Subst. Tributária Oper. Interestadual - Apur. Mensal',
	'wlw-select_key:{actionForm.tipoPagamento}' => 1,
	'wlw-radio_button_group_key:{pageFlow.documentoForm.tipoDocumento}' => 'GNRE',
	'{pageFlow.documentoForm.dataPagamento}' => '01/10/2018' ,
	'wlw-select_key:{pageFlow.documentoForm.natureza}' => '2' ,
	'wlw-select_key:{pageFlow.documentoForm.produtoNatureza}' => '396',
	'{pageFlow.documentoForm.cnpjCpf}' => '10464223000163',
	'{pageFlow.documentoForm.inscEstadual}' => '92035476',
	'{pageFlow.documentoForm.nomeRazaoSocial}' => 'PRIVALIA SERVICOS DE INFORMACAO LTDA',
	'{pageFlow.documentoForm.endereco}' => 'PROFESSOR ALCEU MAYNARD ARAUJO, 698 TERREO',
	'wlw-select_key:{pageFlow.documentoForm.ufContribuinte}' => 'SP',
	'{pageFlow.documentoForm.ufContribuinte}' => 'SP',
	'{pageFlow.documentoForm.municipioContribuinte}' => 'SÃO PAULO',
	'{pageFlow.documentoForm.cepContribuinte}' => '4726160',
	'{pageFlow.documentoForm.dddContribuinte}' => '011',
	'{pageFlow.documentoForm.telefoneContribuinte}' => '35098145',
	'wlw-radio_button_group_key:{pageFlow.documentoForm.icPeriodoOperacao}' => 'porPeriodo',
	'wlw-select_key:{pageFlow.documentoForm.tipoPeriodo}' => 'M',
	'{pageFlow.documentoForm.numeroNf}' => '',
	'{pageFlow.documentoForm.serieNf}' => '',
	'wlw-select_key:{pageFlow.documentoForm.tipoNf}' => '0',
	'{pageFlow.documentoForm.dtEmissaoNf}' => '',
	'{pageFlow.documentoForm.cnpjCpfNf}' => '',
	'{pageFlow.documentoForm.justificativaIsento}' => '',
	'{pageFlow.documentoForm.valorAdicionalFrete}' => '',
	'{pageFlow.documentoForm.valorOutrasDespesas}' => '',
	'{pageFlow.documentoForm.especificacaoOutrasDesp}' => '',
	'{pageFlow.documentoForm.numDocumento}' => '',
	'{pageFlow.documentoForm.periodoReferencia}' => '09/2018',
	'{pageFlow.documentoForm.dataFatoGerador}' => '',
	'{pageFlow.documentoForm.diaVencimento}' => '',
	'{pageFlow.documentoForm.dataVencimento}' => '10/10/2018',
	'{pageFlow.documentoForm.tpDeclaracao}' => '',
	'{pageFlow.documentoForm.tpDeclaracaoCod}' => '',
	'{pageFlow.documentoForm.dataInicioTermoResp}' => '',
	'{pageFlow.documentoForm.dataFimTermoResp}' => '',
	'{pageFlow.documentoForm.justificativa}' => '',
	'{pageFlow.documentoForm.icmsInformado}' => '5,00',
	'{pageFlow.documentoForm.icmsCompensado}' => '0,00',
	'{pageFlow.documentoForm.icmsAposCompensacao}' => '5,00',
	'{pageFlow.documentoForm.fecpInformado}' => '0,00',
	'{pageFlow.documentoForm.icmsAtualizado}' => '5,00',
	'{pageFlow.documentoForm.moraIcms}' => '0,00',
	'{pageFlow.documentoForm.valorMultaMoraICMS}' => '0,00',
	'{pageFlow.documentoForm.totalIcms}' => '5,00',
	'{pageFlow.documentoForm.fecpAtualizado}' => '0,00',
	'{pageFlow.documentoForm.valorMoraFECP}' => '0,00',
	'{pageFlow.documentoForm.valorMultaMoraFECP}' => '0,00',
	'{pageFlow.documentoForm.totalFecp}' => '0,00',
);


$cw = new Focus599Dev\Crawler\Crawler($data);

$cw->getBoleto();

?>