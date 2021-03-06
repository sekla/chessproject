<?php

namespace LogicNow\Test;

use LogicNow\ChessBoard;
use LogicNow\MovementTypeEnum;
use LogicNow\Pawn;
use LogicNow\PieceColorEnum;


class PawnTest extends \PHPUnit_Framework_TestCase
{

    /** @var  ChessBoard */
    private $_chessBoard;
    /** @var  Pawn */
    private $_testSubject;

    protected function setUp()
    {
        $this->_chessBoard = new ChessBoard();
        $this->_testSubject = new Pawn(PieceColorEnum::WHITE());

    }

    public function testMove_Sets_ChessBoard_New_Position_Occupied()
    {
        $this->_chessBoard->add($this->_testSubject, 2, 3, PieceColorEnum::BLACK());
		$this->_testSubject->move(MovementTypeEnum::MOVE(), 2, 2);
		$isPositionOccupied = $this->_chessBoard->isPositionOccupied(2,2);
		$this->assertTrue($isPositionOccupied);
    }

    public function testMove_Sets_ChessBoard_Old_Position_Free()
    {
        $this->_chessBoard->add($this->_testSubject, 2, 3, PieceColorEnum::BLACK());
		$this->_testSubject->move(MovementTypeEnum::MOVE(), 2, 2);
		$isPositionOccupied = $this->_chessBoard->isPositionOccupied(2,3);
		$this->assertFalse($isPositionOccupied);
    }
	
	public function testPawn_Move_LegalCoordinates_Forward_UpdatesCoordinates_For_Whites()
    {
        $this->_chessBoard->add($this->_testSubject, 6, 3, PieceColorEnum::WHITE());
        $this->_testSubject->move(MovementTypeEnum::MOVE(), 6, 4);
        $this->assertEquals(6, $this->_testSubject->getXCoordinate());
        $this->assertEquals(4, $this->_testSubject->getYCoordinate());
    }
}