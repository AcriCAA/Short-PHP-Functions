
<?php
/*
 * =======================================================================
 * -----------------------------------------------------------------------
 *    MySQLi Resultset to XML
 *          - Generate formatted XML file from Query
 * -----------------------------------------------------------------------
 *       - Title: QueryToXML
 *
 *       - Original Author: Bert Maurau
 * =======================================================================
 */

class QueryToXML {
   public $XMLFileName = 'MyNotes.xml';
   public $XMLNodeRoot = '<notes></notes>';
   public $XMLNodeSub = 'note';
}

function QueryToXML($Data, &$XML) {
   global $QueryToXML;

   foreach ($Data as $Key => $Value) {
      if (is_array($Value)) {
         if (!is_numeric($Key)) {
            $SubNode = $XML -> addChild("$Key");
            WriteAsXML($Value, $SubNode);
         } else {
            $SubNode = $XML -> addChild("$QueryToXML->XMLNodeSub");
            WriteAsXML($Value, $SubNode);
         }
      } else {
         $XML -> addChild("$Key", htmlspecialchars("$Value"));
      }
   }
}
?>
