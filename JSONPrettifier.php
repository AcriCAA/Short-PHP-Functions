<?php
/*
 * =======================================================================
 * -----------------------------------------------------------------------
 *    Pretty Print JSON String
 *          - Replacement for PHP's pretty_print > 5.4
 * -----------------------------------------------------------------------
 *       - Title: prettifyJSON
 *
 *       - Original Author: Dave Perrett
 *       - Modified By:
 *          - Dmitry Gryanko: For dealing better with escaped sequences
 *          - Bert Maurau: Added HTML mode (display, colors, ..)
 * =======================================================================
 */

class prettifyJSON {
   public $HTMLMode = true;
   //Activate HTML display
   public $HTMLElement = 'span';
   //Element Type for key-element
   public $HTMLClass = 'prettify-key';
   //Classname for key-element
   public $HTMLIndent = '&emsp;';
   //Character for indentation
   public $HTMLNewLine = '<br>';
   //Element for new line
}

function prettifyJSON($JSONString, $HTMLConfig) {

   $PrettifiedResult = '';
   $Position = 0;
   $JSONLength = strlen($JSONString);

   // HTML Mode Config
   $HTML = $HTMLConfig -> HTMLMode;
   $Element = $HTMLConfig -> HTMLElement;
   $Class = $HTMLConfig -> HTMLClass;

   $CharIndent = ($HTML == true) ? $HTMLConfig -> HTMLIndent : "\t";
   $CharNewline = ($HTML == true) ? $HTMLConfig -> HTMLNewLine : "\n";

   for ($i = 0; $i < $JSONLength; $i++) {
      // Grab the next character in the string.
      $char = $JSONString[$i];

      // Check for quoted string
      if ($char == '"') {
         // If so.. Check for ending
         if (!preg_match('`"(\\\\\\\\|\\\\"|.)*?"`s', $JSONString, $Matches, null, $i))
            return $JSONString;

         // add extracted string to the result and move ahead
         if ($HTML == true) {
            // Dirty but it works
            $PrettifiedResult .= (substr($JSONString, strpos($JSONString, $Matches[0]) + strlen($Matches[0]), 1) == ':') ? '<' . $Element . ' class="' . $Class . '">' . $Matches[0] . '</' . $Element . '>' : $Matches[0];

         } else {
            $PrettifiedResult .= $Matches[0];
         }

         $i += strLen($Matches[0]) - 1;
         continue;

      } else if ($char == '}' || $char == ']') {
         $PrettifiedResult .= $CharNewline;
         $Position--;
         $PrettifiedResult .= str_repeat($CharIndent, $Position);
      }

      // Add the character to the result string.
      $PrettifiedResult .= $char;

      // If the last character was the beginning of an element,
      // output a new line and indent the next line.
      if ($char == ',' || $char == '{' || $char == '[') {
         $PrettifiedResult .= $CharNewline;
         if ($char == '{' || $char == '[') {
            $Position++;
         }

         $PrettifiedResult .= str_repeat($CharIndent, $Position);
      }
   }

   return $PrettifiedResult;
}
?>
