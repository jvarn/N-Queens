<?php

/**
 * Queens class.
 * Solves N-Queens problem: put N Queens on a chess board NxN
 * sized such that they aren't at risk of capture
 *
 * Adapted from: https://github.com/rcammisola/N-Queens
 */

class Queens
{
	public  $title = "Queens Challenge";

	private $queen,
			$board = array(),
			$number_of_queens = 8, // default 8 because actual chessboard size (8x8)
			$numbers,
			$from_number,
			$to_number,
			$min_number = 4,
			$max_number = 30, // it's recursive, remember
			$navbar;

	/**
	 * construct method.
	 * Pass two integers to override the default numbers in the select box
	 *
	 * @param integer $from_number the starting number for the range displayed in the select box
	 * @param integer $to_number the ending number for the range displayed in the select box
	 */
	public function __construct( $from_number = 4, $to_number = 20 ) {
		$this->check_range( $from_number, $to_number );
		$this->queen = $this->svg_queen();
		$this->get_number_of_queens();
		$this->init_board( $this->number_of_queens );
		$this->solve();		
	}
	
	/**
	 * check_range method.
	 * Sets the highest and lowest number of queens within upper and lower limits
	 * and creates an array of numbers between that range 
	 *
	 * @access private
	 * @param integer $from_number the starting number for the range displayed in the select box
	 * @param integer $to_number the ending number for the range displayed in the select box
	 */
	private function check_range( $from_number, $to_number ) {
		$this->from_number = ( (int)$from_number >= $this->min_number ? (int)$from_number : $this->min_number );					
		$this->to_number = ( (int)$to_number <= $this->max_number ? (int)$to_number : $this->max_number );
		$this->numbers = range( $this->from_number, $this->to_number );			
	}
	
	/**
	 * get_number_of_queens method.
	 * Checks if the select box has been submitted and sets the number_of_queens variable
	 *
	 * @access private
	 */
	private function get_number_of_queens() {
		if ( isset( $_GET['n'] ) ) {
			$this->number_of_queens = $_GET['n'];
		}
	}
	
	/**
	 * init_board method.
	 * Fills the chess_board array with 0s
	 *
	 * @access private
	 * @param integer $n the number of queens selected
	 */
	private function init_board( $n ) {
		for ( $i = 0; $i < $n; $i++ ) {
			$this->chess_board[$i] = array_fill(0, $n, 0);
		}
	}

	/**
	 * solve method.
	 * Recursively fills the chess_board array with 1s for allowed positions
	 *
	 * @access private
	 * @param integer $queen_number the current queen number in the loop
	 * @param integer $row the current row number in the loop
	 */
	private function solve( $queen_number = 0, $row = 0 ) {
		for ( $col = 0; $col < $this->number_of_queens; $col++ ) {
			if ( $this->allowed_cell( $row, $col ) ) {
				// if this cell is allowed, set the queen here
				$this->chess_board[$row][$col] = 1;

				// If last queen or subsequent queens have been placed, return
				if(($queen_number === $this->number_of_queens - 1) || $this->solve( $queen_number + 1, $row + 1 ) === true) return true;

				// otherwise, if we get here we've backtracked and have to try replacing this queen
				$this->chess_board[$row][$col] = 0;
			}
		}
		return false;
	}

	/**
	 * allowed_cells method.
	 * Checks if any cells in the same row, column, or diagonal already exists
	 *
	 * @access private
	 * @param integer $x the row number
	 * @param integer $y the column number
	 */
	private function allowed_cell($x, $y) {
		$n = $this->number_of_queens;

		// Only test as far as the row being entered because there will never
		// be a situation where a Queen is moved behind other Queens.
		// Any further rows will all be empty.
		for($i = 0; $i < $x; $i++) {
			// test the column to check for another Queen
			if($this->chess_board[$i][$y] === 1) return false;

			// Test the diagonals (backwards from the coordinate)
			$tx = $x - 1 - $i;
			$ty = $y - 1 - $i; // diagonal this way \
			if(($ty >= 0) && ($this->chess_board[$tx][$ty] === 1)) return false;

			$ty = $y + 1 + $i; // diagonal this way /
			if(($ty < $n) && ($this->chess_board[$tx][$ty] === 1)) return false;
		}
		return true;
	}

	/**
	 * make_board_table method.
	 * Constructs a text-based grid with selected dimensions, and inserts the letter Q 
	 * into cells corresponding to positions in the array with value 1
	 *
	 * @access private
	 * @return string $board the completed chess board as a html table
	 */
	private function make_board_text() {
		$board = '<pre>';
		for ( $row=0; $row < $this->number_of_queens; $row++ ) {
			$seperator = '-';
			for ( $col=0; $col < $this->number_of_queens; $col++ ) {
				$seperator .= '--';	// for every column add 2 dashes to then print below the row
				$board .= '|';
				
				$cell = $this->chess_board[$row][$col];
				if ( $cell === 1 ) {
					$board .= 'Q';
				} else {
					$board .= ' ';
				}
			}
			
			$board .= "|"."\n";
			$board .= $seperator . "\n";	// add the seperator row -------
		}
		$board .= '</pre>';
		return $board;
	}

	/**
	 * make_board_table method.
	 * Constructs a table with selected dimensions, classes to create the chequerboard effect
	 * and inserts a queen symbol into cells corresponding to positions in the array with value 1
	 *
	 * @access private
	 * @return string $board the completed chess board as a html table
	 */
	private function make_board_table() {
		$board = '<table class="table table-bordered chessboard">';
		$i=0;
		for ( $row=0; $row < $this->number_of_queens; $row++ ) {
			$board .= '<tr>';
			for ( $col=0; $col < $this->number_of_queens; $col++ ) {
				$oddeven = ($col+$row+2);
				$squarecolor = ( $oddeven % 2 == 0 ? "b" : "w" ); 
				$board .= '<td class="' . $squarecolor . '">';
				$cell = $this->chess_board[$row][$col];
				if ( $cell === 1 )	{ 
					$board .= '<div>' . $this->queen . '</div>';
				} else {
					$board .= '<div></div>';
				} $i++;
				$board .= '</td>';
			}
			$board .= "</tr>";
		}
		$board .= '</table>';

		return $board;
	}

	/**
	 * make_nav method.
	 * Creates a html nav element with bootstrap classes
	 *
	 * @access private
	 * @return string $navbar the html navbar element
	 */
	private function make_nav() {
		$navbar = '<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark" id="navbar" role="navigation">'."\n";
		$navbar .= '<a class="navbar-brand mr-auto" href="#">Choose Board Size:</a>'."\n";
		$navbar .= '<form class="form-inline" action="" method="get">'."\n";
		$navbar .=		'<div class="input-group mr-2">'."\n\n";
		
		$navbar .= $this->make_select();						
		
		$navbar .=		'<div class="input-group-append">'."\n";
		$navbar .=		'<button class="btn btn-outline-light" type="submit">Apply</button>'."\n";
		$navbar .=		'</div>'."\n";
		$navbar .=	'</div>'."\n";
		$navbar .=	'</form>'."\n";
		$navbar .= '</nav>'."\n";
		
		return $navbar;
	}

	/**
	 * make_select method.
	 * Creates a html select element filled with the range of numbers created by check_range
	 *
	 * @access private
	 * @return string $select the html select element
	 */
	private function make_select() {
		if ( isset( $_GET['n'] ) ) { 
			$n = (int)$_GET['n'];
			$this->number_of_queens = $n;
		} else {
			$n = $this->number_of_queens;
		}
							
		foreach ( $this->numbers as $num ) {
			$selected = ( $num === $n ? "selected" : "" );
			$options[] = '<option value="'. $num . '" ' . $selected . '>' . $num . '</option>'; 
		}
		
		$select  = '<select class="custom-select" id="n" name="n">'."\n";
		$select .= implode( "", $options );
		$select .= '</select>'."\n";
		
		return $select;
	}

	/**
	 * make_head method.
	 * Creates essential elements to be inserted into header if using styled output
	 *
	 * @access private
	 * @return string $select the html select element
	 */
	private function make_head() {
		$header =  '<meta name="viewport" content="width=device-width, initial-scale=1">'."\n";
		$header .=  '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'."\n";
		$header .= '<link rel="stylesheet" href="style.css">'."\n";

		return $header;
	}
	/**
	 * svg_queen method.
	 * An SVG queen symbol
	 *
	 * @access private
	 * @link https://thenounproject.com/term/queen/990748
	 * @return string $svg_queen the queen symbol
	 */
	private function svg_queen() {	
	$svg_queen = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 99.2 99.2" style="" xml:space="preserve"><g><path d="M30.6,86.5c4.2,1.4,11.2,2,19.4,2c7.3,0,14.1-0.4,18.5-1.6v5.7c-5.3,0.7-11.5,1.1-18.1,1.1c-7.2,0-14-0.5-19.6-1.3L30.6,86.5z"/><path d="M66.4,78.1c-3.4-3.9-4.4-6.7-2.6-7.7c1.7-1.1,1-2.2-0.7-2.8c-1.6-0.6-2.8-1.5-4.9-6.8c-2.1-5.3-1.9-17.2-1.9-18.7c0-1.2,0.6-1.1,3.2-1.1c0.4,0,0.7,0,0.8,0c1.2,0,2.2-0.8,2.2-2s-1-2-2.2-2h-0.2c-0.8,0-1.2-0.4-0.7-0.7l0,0c0.6-0.2,1.1-0.8,1.1-1.5c0-0.9-0.7-1.6-1.5-1.6h-1.4c-0.6,0-0.5-0.7-0.1-0.7s1-0.6,1-1.3s-0.6-1.3-1.2-1.3c-0.3-2.7,0.4-8.5,3.7-10.1c3.3-1.7,2.2-2,1.6-2.9c-0.7-0.9-2.1-1.2-3.2-1.1c-1.1,0-1.1,0.1-1.5-0.9s-3.8-5.2-8.3-5.2s-8,4.2-8.4,5.2s-0.3,0.8-1.4,0.8s-2.5,0.2-3.2,1.1c-0.7,0.9-1.7,1.3,1.6,2.9c3.3,1.7,4,7.4,3.7,10.1c-0.7,0-1.2,0.6-1.2,1.3s0.6,1.3,1,1.3s0.5,0.7-0.1,0.7h-1.4c-0.8,0-1.5,0.7-1.5,1.6c0,0.7,0.5,1.4,1.1,1.5l0,0c0.6,0.3,0.2,0.7-0.7,0.7h-0.2c-1.2,0-2.2,0.7-2.2,2s1,2,2.2,2c0.1,0,0.3,0,0.8,0c2.6,0,3.2-0.1,3.2,1.1c0,1.5,0.2,13.4-1.9,18.7c-2.1,5.3-3.2,6.2-4.9,6.8c-1.6,0.6-2.4,1.7-0.7,2.8c1.7,1.1,0.7,3.9-2.6,7.7c-3.7,4.2-2.1,6.8-0.4,7.6c3.9,1.3,9.6,1.9,17.2,1.9s13.3-0.6,17.2-1.9C68.5,84.9,70.1,82.3,66.4,78.1z"/><ellipse cx="49.6" cy="6.7" rx="3.3" ry="3"/></g></svg><!-- queen by Dolly Holmes from the Noun Project -->';
		return $svg_queen;
	}

	/**
	 * insert_nav method.
	 * Outputs the navbar
	 *
	 * @access public
	 */
	public function insert_nav() {
		echo $this->make_nav();
	}
	
	/**
	 * insert_select method.
	 * Outputs the select element
	 *
	 * @access public
	 */
	public function insert_select() {
		echo $this->make_select();
	}

	/**
	 * insert_head method.
	 * Outputs the header elements (for styled display)
	 *
	 * @access public
	 */
	public function insert_head() {
		echo $this->make_head();
	}
	
	/**
	 * print_board method.
	 * Outputs the completed chess board
	 *
	 * @access public
	 * @param $plain mixed outputs the plain text version of the chess board if set
	 */
	public function print_board( $plain=null ) {
		if ( !isset( $plain ) ) {
			echo $this->make_board_table();
		} else {
			echo $this->make_board_text();
		}
	}

}
?>