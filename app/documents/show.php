<?php

//header
include("../includes/docheader.php");

$xsl=new DomDocument();
$xsl->load("../xslt/short.xsl");

$inputDom=new DomDocument();
$inputDom->load("../../xml_added/$_GET[sangerDoc]");

$proc=new XsltProcessor();
$xsl=$proc->importStylesheet($xsl);

$result=$proc->transformToXML($inputDom);

if ($result) {
  print $result;
}
else {
  print "Sorry, $_GET[sangerDoc] could not be transformed by short.xsl into";
  print "  $_GET[sangerDoc].  The reason is that " . xslt_error($xh) . " and the ";
  print "error code is " . xslt_errno($xh).".";
}

//footer
include("../includes/footer.php");
?>
