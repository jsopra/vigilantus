<?php

require_once 'acceptance/WebGuy.php';

use tests\_pages\LoginPage;

/**
 * Traduz métodos da WebGuy para o português
 */
class CaraDaWeb extends WebGuy
{
    /**
     * Autentica-se com um nome de usuário
     * @param string $login Login do usuário
     */
    public function facoLoginComo($login, $senha)
    {
        $eu = $this;
        $paginaDeLogin = LoginPage::openBy($eu);
        $paginaDeLogin->login($login, $senha);
        $eu->vejo('Logout (' . $login . ')');
    }

    /**
     * Espera por N segundos
     * @param integer $segundos
     */
    public function aguardoPor($segundos)
    {
        sleep($segundos);
    }

    /**
     * Clica em um link no grid (exemplo: existem vários botões editar na tela)
     * @param string|integer $id Um label em uma coluna ou o ID do registro
     * @param string $botao Seletor do botão ou link
     */
    public function clicoNoGrid($id, $botao)
    {
        $this->clico("//tr//td[text()='$id']//parent::tr//td//a[@title='$botao' or text()='$botao']");
    }

    /**
     * @inheritdoc
     */
    public function envioFormulario($selector, $params)
    {
        foreach ($params as $field => $value) {
            $this->preenchoCampo($field, $value);
        }

        $this->clico('//button[@type=\'submit\']', $selector);
        $this->aguardoPor(1);
        
        //return parent::submitForm($selector, $params);
    }

    // ##### ALIAS PARA MÉTODOS DO CODECEPTION

    /**
     * @inheritdoc Selenium2
     */
    public function vejoNaPopUp($text)
    {
        return parent::seeInPopup($text);
    }

    /**
     * @inheritdoc Selenium2
     */
    public function naoVejoNaPopUp($texto)
    {
        return parent::dontSeeInPopup($text);
    }

    /**
     * @inheritdoc Selenium2
     */
    public function aceitoPopUp()
    {
        return parent::acceptPopup();
    }

    /**
     * @inheritdoc Selenium2
     */
    public function canceloPopUp()
    {
        return parent::cancelPopup();
    }

    /**
     * @inheritdoc
     */
    public function quero($text)
    {
        return parent::wantTo($text);
    }

    /**
     * @inheritdoc
     */
    public function espero($prediction)
    {
        return parent::expectTo($prediction);
    }

    /**
     * @inheritdoc
     */
    public function esperoPor($prediction)
    {
        return parent::expect($prediction);
    }

    /**
     * @inheritdoc
     */
    public function vou($argumentation)
    {
        return parent::amGoingTo($argumentation);
    }

    /**
     * @inheritdoc
     */
    public function sou($role)
    {
        return parent::am($role);
    }

    /**
     * @inheritdoc
     */
    public function esperoQue($achieveValue)
    {
        return parent::lookForwardTo($achieveValue);
    }

    /**
     * @inheritdoc
     */
    public function envioAjaxPost($uri, $params = null) {
        return parent::sendAjaxPostRequest($uri, $params);
    }

    /**
     * @inheritdoc
     */
    public function envioAjaxGet($uri, $params = null) {
        return parent::sendAjaxGetRequest($uri, $params);
    }

    /**
     * @inheritdoc
     */
    public function envioAjax($method, $uri, $params = null) {
        return parent::sendAjaxRequest($method, $uri, $params);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerPaginaNaoEncontrada() {
        return parent::canSeePageNotFound();
    }

    /**
     * @inheritdoc
     */
    public function vejoPaginaNaoEncontrada() {
        return parent::seePageNotFound();
    }

    /**
     * @inheritdoc
     */
    public function possoVerCodigoResposta($code) {
        return parent::canSeeResponseCodeIs($code);
    }

    /**
     * @inheritdoc
     */
    public function vejoCodigoResposta($code) {
        return parent::seeResponseCodeIs($code);
    }
 
    /**
     * @inheritdoc
     */
    public function estouAutenticadoViaHttp($username, $password) {
        return parent::amHttpAuthenticated($username, $password);
    }

    /**
     * @inheritdoc
     */
    public function executoNoGuzzle($function) {
        return parent::executeInGuzzle($function);
    }

    /**
     * @inheritdoc
     */
    public function possoVerCheckboxEstaChecada($checkbox) {
        return parent::canSeeCheckboxIsChecked($checkbox);
    }

    /**
     * @inheritdoc
     */
    public function vejoCheckboxEstaChecada($checkbox) {
        return parent::seeCheckboxIsChecked($checkbox);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVerCheckboxEstaChecada($checkbox) {
        return parent::cantSeeCheckboxIsChecked($checkbox);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoCheckboxEstaChecada($checkbox) {
        return parent::dontSeeCheckboxIsChecked($checkbox);
    }

    /**
     * @inheritdoc
     */
    public function estouNaPagina($page) {
        return parent::amOnPage($page);
    }
 
    /**
     * @inheritdoc
     */
    public function estouNoSubdominio($subdomain) {
        return parent::amOnSubdomain($subdomain);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVer($text, $selector = null) {
        return parent::cantSee($text, $selector);
    }
    /**
     * @inheritdoc
     */
    public function naoVejo($text, $selector = null) {
        return parent::dontSee($text, $selector);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVer($text, $selector = null) {
        return parent::canSee($text, $selector);
    }

    /**
     * @inheritdoc
     */
    public function vejo($text, $selector = null) {
        return parent::see($text, $selector);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerLink($text, $url = null) {
        return parent::canSeeLink($text, $url);
    }
    /**
     * @inheritdoc
     */
    public function vejoLink($text, $url = null) {
        return parent::seeLink($text, $url);
    }

    /**
     * @inheritdoc
     */
    public function naoPossoVerLink($text, $url = null) {
        return parent::cantSeeLink($text, $url);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoLink($text, $url = null) {
        return parent::dontSeeLink($text, $url);
    }
 
    /**
     * @inheritdoc
     */
    public function clico($link, $context = null) {
        return parent::click($link, $context);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerElemento($selector) {
        return parent::canSeeElement($selector);
    }
    /**
     * @inheritdoc
     */
    public function vejoElemento($selector) {
        return parent::seeElement($selector);
    }

    /**
     * @inheritdoc
     */
    public function naoPossoVerElemento($selector) {
        return parent::cantSeeElement($selector);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoElemento($selector) {
        return parent::dontSeeElement($selector);
    }
 
    /**
     * @inheritdoc
     */
    public function recarregoPagina() {
        return parent::reloadPage();
    }
 
    /**
     * @inheritdoc
     */
    public function clicoEmVoltar() {
        return parent::moveBack();
    }

    /**
     * @inheritdoc
     */
    public function clicoEmAvancar() {
        return parent::moveForward();
    }

    /**
     * @inheritdoc
     */
    public function preenchoCampo($field, $value) {
        return parent::fillField($field, $value);
    }
 
    /**
     * @inheritdoc
     */
    public function selecionoOpcao($select, $option) {
        return parent::selectOption($select, $option);
    }
 
    /**
     * @inheritdoc
     */
    public function marcoOpcao($option) {
        return parent::checkOption($option);
    }
 
    /**
     * @inheritdoc
     */
    public function desmarcoOpcao($option) {
        return parent::uncheckOption($option);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerNaUrlAtual($uri) {
        return parent::canSeeInCurrentUrl($uri);
    }

    /**
     * @inheritdoc
     */
    public function vejoNaUrlAtual($uri) {
        return parent::seeInCurrentUrl($uri);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVerNaUrlAtual($uri) {
        return parent::cantSeeInCurrentUrl($uri);
    }
    /**
     * @inheritdoc
     */
    public function naoVejoNaUrlAtual($uri) {
        return parent::dontSeeInCurrentUrl($uri);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerUrlAtualIgualA($uri) {
        return parent::canSeeCurrentUrlEquals($uri);
    }

    /**
     * @inheritdoc
     */
    public function vejoUrlAtualIgualA($uri) {
        return parent::seeCurrentUrlEquals($uri);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVerUrlAtualIgualA($uri) {
        return parent::cantSeeCurrentUrlEquals($uri);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoUrlAtualIgualA($uri) {
        return parent::dontSeeCurrentUrlEquals($uri);
    }
 
    /**
     * @inheritdoc
     */
    public function canSeeCurrentUrlMatches($uri) {
        return parent::canSeeCurrentUrlMatches($uri);
    }

    /**
     * @inheritdoc
     */
    public function seeCurrentUrlMatches($uri) {
        return parent::seeCurrentUrlMatches($uri);
    }

    /**
     * @inheritdoc
     */
    public function cantSeeCurrentUrlMatches($uri) {
        return parent::cantSeeCurrentUrlMatches($uri);
    }

    /**
     * @inheritdoc
     */
    public function dontSeeCurrentUrlMatches($uri) {
        return parent::dontSeeCurrentUrlMatches($uri);
    }

    /**
     * @inheritdoc
     */
    public function possoVerCookie($cookie) {
        return parent::canSeeCookie($cookie);
    }

    /**
     * @inheritdoc
     */
    public function vejoCookie($cookie) {
        return parent::seeCookie($cookie);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVerCookie($cookie) {
        return parent::cantSeeCookie($cookie);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoCookie($cookie) {
        return parent::dontSeeCookie($cookie);
    }
 
    /**
     * @inheritdoc
     */
    public function setoCookie($cookie, $value) {
        return parent::setCookie($cookie, $value);
    }
 
    /**
     * @inheritdoc
     */
    public function resetoCookie($cookie) {
        return parent::resetCookie($cookie);
    }
 
    /**
     * @inheritdoc
     */
    public function pegoCookie($cookie) {
        return parent::grabCookie($cookie);
    }
 
    /**
     * @inheritdoc
     */
    public function pegoDaUrlAtual($uri = null) {
        return parent::grabFromCurrentUrl($uri);
    }
 
    /**
     * @inheritdoc
     */
    public function anexoArquivo($field, $filename) {
        return parent::attachFile($field, $filename);
    }

    /**
     * @inheritdoc
     */
    public function possoVerOpcaoSelecionada($select, $text) {
        return parent::canSeeOptionIsSelected($select, $text);
    }

    /**
     * @inheritdoc
     */
    public function vejoOpcaoSelecionada($select, $text) {
        return parent::seeOptionIsSelected($select, $text);
    }

    /**
     * @inheritdoc
     */
    public function naoPossoVerOpcaoSelecionada($select, $text) {
        return parent::cantSeeOptionIsSelected($select, $text);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoOpcaoSelecionada($select, $text) {
        return parent::dontSeeOptionIsSelected($select, $text);
    }
 
    /**
     * @inheritdoc
     */
    public function possoVerNoCampo($field, $value) {
        return parent::canSeeInField($field, $value);
    }

    /**
     * @inheritdoc
     */
    public function vejoNoCampo($field, $value) {
        return parent::seeInField($field, $value);
    }
 
    /**
     * @inheritdoc
     */
    public function naoPossoVerNoCampo($field, $value) {
        return parent::cantSeeInField($field, $value);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoNoCampo($field, $value) {
        return parent::dontSeeInField($field, $value);
    }
 
    /**
     * @inheritdoc
     */
    public function pegoTextoDe($cssOrXPathOrRegex) {
        return parent::grabTextFrom($cssOrXPathOrRegex);
    }

    /**
     * @inheritdoc
     */
    public function pegoValorDe($field) {
        return parent::grabValueFrom($field);
    }

    /**
     * @inheritdoc
     */
    public function possoVerNoTitulo($title) {
        return parent::canSeeInTitle($title);
    }
    /**
     * @inheritdoc
     */
    public function vejoNoTitulo($title) {
        return parent::seeInTitle($title);
    }

    /**
     * @inheritdoc
     */
    public function naoPossoVerNoTitulo($title) {
        return parent::cantSeeInTitle($title);
    }

    /**
     * @inheritdoc
     */
    public function naoVejoNoTitulo($title) {
        return parent::dontSeeInTitle($title);
    }
}