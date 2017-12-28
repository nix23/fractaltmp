<?php
namespace Wasm\ModRenderBundle\Twig;

class TwigLexer extends \Twig_Lexer
{
    protected function lexData()
    {
        // if no matches are left we return the rest of the template as simple text token
        if ($this->position == count($this->positions[0]) - 1) {
            $this->pushToken(\Twig_Token::TEXT_TYPE, substr($this->code, $this->cursor));
            $this->cursor = $this->end;

            return;
        }

        // Find the first token after the current cursor
        $position = $this->positions[0][++$this->position];
        while ($position[1] < $this->cursor) {
            if ($this->position == count($this->positions[0]) - 1) {
                return;
            }
            $position = $this->positions[0][++$this->position];
        }

        // push the template text first
        $text = $textContent = substr($this->code, $this->cursor, $position[1] - $this->cursor);
        $textArr = str_split($text);
        // keep "import" " from" like strings
        $pos = strrpos($text, "\n"); // New rtrim
        $text = substr($text, 0, $pos+1).rtrim(substr($text, $pos+1)); // New rtrim
        //var_dump($text);
        // Replaced with upper
        //if (isset($this->positions[2][$this->position][0])) {
            //$text = rtrim($text);
        //}

        $areOnOneLine = function($position1, $position2) {
            $areOnOneLine = true;
            $textArray = str_split($this->code);
            // echo "p1: " . $position1;
            // echo "p2: " . $position2;
            // echo "text: " . $this->code;
            for($i = $position1; $i <= $position2; $i++) {
                if($textArray[$i] == "\n") {
                    $areOnOneLine = false;
                    break;
                }
            }

            return $areOnOneLine;
        };


        // Find the next token after the current cursor
        $thisPosition = $this->position;
        $nextText = "";
        $pposition = null;
        if ($thisPosition < count($this->positions[0]) - 1) {
            $pposition = $this->positions[0][++$thisPosition];
            while ($pposition[1] < $this->cursor) {
                if ($thisPosition == count($this->positions[0]) - 1) {
                    return;
                }

                $pposition = $this->positions[0][++$thisPosition];
            }
            $nextText = substr($this->code, $this->cursor, $pposition[1] - $this->cursor);
            // echo "Cursor: " . $this->cursor;
            // echo "Position1: "; var_dump($position);
            // echo "Position2: "; var_dump($pposition);
            // echo "AreOnOneLine: "; var_dump($areOnOneLine($position[1], $pposition[1]));

            $textArray = str_split($text);
            $nextTextArray = str_split($nextText);

            // var_dump(implode("", $textArray));
            // var_dump(implode("", $nextTextArray));
            // echo "\n\n\n";
            $spacesCount = 0;
            // for($i = 0; $i < count($textArray); $i++) {
            //     if($textArray[$i] != " ")
            //         break;
            //     $spacesCount++;
            // }
            for($i = 0; $i < count($nextTextArray); $i++) {
                if($nextTextArray[$i] != " ")
                    break;
                $spacesCount++;
            }

            /*
                Check if end of curr string in nextString and new content
                of nextString are placed without any spaces between them.
                If no spaces -> cancel spaces insertion
            "                <RenderIf."
            "                <RenderIf.<%layout.renderIfComponentName%>>
            */
            $endOfTextArray = array();
            for($i = count($textArray) - 1; $i--; $i >= 0) {
                if($textArray[$i] == " ")
                    break;

                $endOfTextArray[] = $textArray[$i];
            }
            $endOfTextArray = array_reverse($endOfTextArray);

            $lastCharOfEndOfTextIndex = strrpos($text, implode("", $endOfTextArray));
            if($lastCharOfEndOfTextIndex !== false) {
                $lastCharOfEndOfTextIndex += count($endOfTextArray);
                if($nextTextArray[$lastCharOfEndOfTextIndex + 1] != " ")
                    $spacesCount = 0;
            }

            // $textOnlySpaces = true;
            // for($i = 0; $i < count($textArray); $i++) {
            //     if($textArray[$i] != " ") {
            //         $textOnlySpaces = false;
            //         break;
            //     }
            // }

            //if($textOnlySpaces && $spacesCount > 1)
                $spacesCount--;

            for($i = 0; $i < $spacesCount; $i++)
                $text .= " ";
            
            // Check if ends with not space (import) and next with space ('path')
            // AND both tokens are on the same string
            if($textArray[count($textArray) - 1] != " " &&
               $nextTextArray[0] != " " &&
               $areOnOneLine($position[1], $pposition[1])) {
                // echo "***********\n";
                // var_dump($text);
                // var_dump($nextText);
                // echo "***********\n";
                $text .= " ";
            }
        }



        $this->pushToken(\Twig_Token::TEXT_TYPE, $text);
        $this->moveCursor($textContent.$position[0]);

        switch ($this->positions[1][$this->position][0]) {
            case $this->options['tag_comment'][0]:
                $this->lexComment();
                break;

            case $this->options['tag_block'][0]:
                // raw data?
                if (preg_match($this->regexes['lex_block_raw'], $this->code, $match, null, $this->cursor)) {
                    $this->moveCursor($match[0]);
                    $this->lexRawData($match[1]);
                // {% line \d+ %}
                } elseif (preg_match($this->regexes['lex_block_line'], $this->code, $match, null, $this->cursor)) {
                    $this->moveCursor($match[0]);
                    $this->lineno = (int) $match[1];
                } else {
                    $this->pushToken(\Twig_Token::BLOCK_START_TYPE);
                    $this->pushState(\Twig_Lexer::STATE_BLOCK);
                    $this->currentVarBlockLine = $this->lineno;
                }
                break;

            case $this->options['tag_variable'][0]:
                $this->pushToken(\Twig_Token::VAR_START_TYPE);
                $this->pushState(\Twig_Lexer::STATE_VAR);
                $this->currentVarBlockLine = $this->lineno;
                break;
        }
    }
}
