<?php
namespace Common\Dom;

/**
 * Classe auxiliar com funções de DOM extendidas
 * @category   NFePHP
 * @package    NFePHP\Common\DomDocument
 * @copyright  Copyright (c) 2008-2015
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */

use \DOMDocument;
use Common\Files\FilesFolders;
use Common\Exception;

class Dom extends DOMDocument
{
    /**
     * __construct
     * @param type $version
     * @param type $charset
     */
    public function __construct($version = '1.0', $charset = 'utf-8')
    {
        parent::__construct($version, $charset);
        $this->formatOutput = false;
        $this->preserveWhiteSpace = false;
    }

    public function loadXMLString($xmlString = '')
    {
        if (! $this->loadXML($xmlString, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
            $msg = "O arquivo indicado não é um XML!";
            throw new Exception\RuntimeException($msg);
        }
    }
    
    public function loadXMLFile($pathXmlFile = '')
    {
        $data = FilesFolders::readFile($pathXmlFile);
        $this->loadXMLString($data);
    }
            
    /**
     * getNodeValue
     * Extrai o valor do node DOM
     * @param string $nodeName identificador da TAG do xml
     * @param int $itemNum numero do item a ser retornado
     * @param string $extraTextBefore prefixo do retorno
     * @param string $extraTextAfter sufixo do retorno
     * @return string
     */
    public function getNodeValue($nodeName, $itemNum = 0, $extraTextBefore = '', $extraTextAfter = '')
    {
        $node = $this->getElementsByTagName($nodeName)->item($itemNum);
        if (isset($node)) {
            return $extraTextBefore . trim($node->nodeValue) . $extraTextAfter;
        }
        return '';
    }
    
    /**
     * getNode
     * Retorna o node solicitado
     * @param string $nodeName
     * @param integer $itemNum
     * @return DOMElement se existir ou string vazia se não
     */
    public function getNode($nodeName, $itemNum = 0)
    {
        $node = $this->getElementsByTagName($nodeName)->item($itemNum);
        if (isset($node)) {
            return $node;
        }
        return '';
    }
    
    /**
     * getChave
     * @param string $nodeName
     * @return string
     */
    public function getChave($nodeName = 'infNFe')
    {
        $node = $this->getElementsByTagName($nodeName)->item(0);
        if (! empty($node)) {
            $chaveId = $node->getAttribute("Id");
            $chave =  preg_replace('/[^0-9]/', '', $chaveId);
            return $chave;
        }
        return '';
    }
}