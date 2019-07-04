# N-Queens

This is an adaptation of rcammisola's solution to the N-Queens problem solved in PHP with recursion.

The original class output a text-based grid like this:
```
	|Q| | | | | | | |
	-----------------
	| | | | |Q| | | |
	-----------------
	| | | | | | | |Q|
	-----------------
	| | | | | |Q| | |
	-----------------
	| | |Q| | | | | |
	-----------------
	| | | | | | |Q| |
	-----------------
	| |Q| | | | | | |
	-----------------
	| | | |Q| | | | |
	-----------------
```

## Changes
**My changes are mainly cosmetic:**
	- A select box from where you can choose the grid size (number of queens)
	- Added an alternative output style: an HTML table
	- A bit of Bootstrap for its grids, navs, and button styles (chosen because its the CSS framework with which I'm most familiar). Thanks to Bootstrap and a teeny bit of extra CSS, the chess board should fit to the screen without any overflow on any size of device.
	- An SVG queen: "queen by Dolly Holmes from the Noun Project"
	- Wrapped text-based board in a pre tag so it aligns nicely.

**Other changes include:**
	- Modifying how default values are handled (construct/solve methods)
	- Splitting the methods that construct the output from the ones that actually display it
	- PHP formatting changes (based upon my own personal preference, which stems from working with Wordpress). Mostly this means: curly braces open on the same line but close on a new line, and there are spaces inside parenthesis and around operators / operands.
	- You can still output a text-based board by passing anything to the print_board method, e.g. $q->print_board(1); 

