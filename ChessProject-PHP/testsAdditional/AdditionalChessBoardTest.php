<?php

namespace LogicNow\Test;


use LogicNow\ChessBoard;
use LogicNow\PieceColorEnum;
use LogicNow\Pawn;

class ChessBoardTest extends \PHPUnit_Framework_TestCase
{

    /** @var  ChessBoard */
    private $_testSubject;

    public function setUp()
    {
        $this->_testSubject = new ChessBoard();
    }

    public function testCheck_Position_Free()
    {
		$isPositionOccupied = $this->_testSubject->isPositionOccupied(1,4);
		$this->assertFalse($isPositionOccupied);
	}
	
	public function testCheck_Add_Sets_Position_Occupied()
	{
		$pawn = new Pawn(PieceColorEnum::BLACK());
        $this->_testSubject->add($pawn, 1, 4, PieceColorEnum::BLACK());
		$isPositionOccupied = $this->_testSubject->isPositionOccupied(1,4);
		$this->assertTrue($isPositionOccupied);
    }

}