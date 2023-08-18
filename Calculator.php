<?php

// Simple calcultor

class Calculator {

	function calculate($expression) {
		$operators = ['+', '-', '*', '/'];
		$precedence = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];
	
		$outputQueue = [];
		$operatorStack = [];
	
		$tokens = preg_split('/([\+\-\*\/\(\)])/u', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	
		foreach ($tokens as $token) {
			if (is_numeric($token)) {
				$outputQueue[] = (float)$token;
			} elseif (in_array($token, $operators)) {
				while (
					count($operatorStack) > 0 && in_array(end($operatorStack), $operators) && $precedence[end($operatorStack)] >= $precedence[$token]) {
					$outputQueue[] = array_pop($operatorStack);
				}
				$operatorStack[] = $token;
			} elseif ($token === '(') {
				$operatorStack[] = $token;
			} elseif ($token === ')') {
				while (end($operatorStack) !== '(') {
					$outputQueue[] = array_pop($operatorStack);
				}
				array_pop($operatorStack);
			}
		}
	
		while (!empty($operatorStack)) {
			$outputQueue[] = array_pop($operatorStack);
		}
	
		$evalStack = [];
	
		foreach ($outputQueue as $token) {
			if (is_numeric($token)) {
				$evalStack[] = $token;
			} elseif (in_array($token, $operators)) {
				$b = array_pop($evalStack);
				$a = array_pop($evalStack);
				switch ($token) {
					case '+':
						$evalStack[] = $a + $b;
						break;
					case '-':
						$evalStack[] = $a - $b;
						break;
					case '*':
						$evalStack[] = $a * $b;
						break;
					case '/':
						$evalStack[] = $a / $b;
						break;
				}
			}
		}
	
		return $evalStack[0];
	}

}

$calc = new Calculator;

$expression = "(1 + 2) * (3 - 1)";

$result = $calc->calculate($expression);

echo $result;