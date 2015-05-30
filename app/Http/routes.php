<?php

$app->get('/', function() use ($app) {


	// Cria o cliente
	$client = new GuzzleHttp\Client([
		'base_uri' => 'http://academico.uefs.br/NovoPortal/'
	]);

	// Pega o view state e event validation
	$rawGetAcesso = $client->get('Acesso.aspx?AspxAutoDetectCookieSupport=1')->getBody()->read(7000);
	preg_match('/<input(?:.*?)name="__VIEWSTATE"(?:.*?)value="(.*?)"/', $rawGetAcesso, $viewState, false, 2000);
	preg_match('/<input(?:.*?)name="__EVENTVALIDATION"(?:.*?)value="(.*?)"/', $rawGetAcesso, $eventValidation, false, 6000);

	// Faz o login
	$rawPostAcessoResp = $client->post('Acesso.aspx?AspxAutoDetectCookieSupport=1', [
		'form_params'=>[
			'__EVENTTARGET'=>'',
			'__EVENTARGUMENT'=>'',
			'__VIEWSTATE'=>$viewState[1],
			'ctl00$PageContent$LoginPanel$UserName'=>'login',
			'ctl00$PageContent$LoginPanel$Password'=>'senha',
			'ctl00$PageContent$LoginPanel$LoginButton.x'=>29,
			'ctl00$PageContent$LoginPanel$LoginButton.y'=>5,
			'__EVENTVALIDATION'=>$eventValidation[1]
		]
	]);

	var_dump($rawPostAcessoResp->getBody()->read(100000));


});
