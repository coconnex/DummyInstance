<?php
/*
Name of File :- I_DB_Operation.Interface.php
Purpose :-
File Created By :- Abhijeet Gogte
File Created On :- Wednesday, July 17, 2013
File Modified By :- Kaushik Sen
Last Modified On :- Monday, April 01, 2024
Version :- 2
*/

namespace Coconnex\DBFactory\Interfaces;

interface I_DB_Operation
{
	public function load($id);
	public function save();
	public function add();
	public function modify();
	public function remove();
}
