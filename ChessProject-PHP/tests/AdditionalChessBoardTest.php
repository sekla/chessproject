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

    public function testCheck_Free_Position()
    {
		$isPositionOccupied = $this->_testSubject->isPositionOccupied(1,4);
		$this->assertFalse($isPositionOccupied);
	}
	
	public function testCheck_Occupied_Position()
	{
		$pawn = new Pawn(PieceColorEnum::BLACK());
        $this->_testSubject->add($pawn, 1, 4, PieceColorEnum::BLACK());
		$isPositionOccupied = $this->_testSubject->isPositionOccupied(1,4);
		$this->assertTrue($isPositionOccupied);
    }

}