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
    
    public function testLimits_The_Number_Of_Pawns_For_Different_Colors()
    {
        /** "WHITE" loop */
        for ($i = 0; $i < 10; $i++) {
            /** intentionally here is BLACK and in "add" method is WHITE. It helps to check, if color of piece is set before checking limit for it */
            $pawn = new Pawn(PieceColorEnum::BLACK());
            $row = intval($i / ChessBoard::MAX_BOARD_WIDTH);
            $this->_testSubject->add($pawn, 1 + $row, $i % ChessBoard::MAX_BOARD_WIDTH, PieceColorEnum::WHITE());
            if ($row < 1) {
                $this->assertEquals(1 + $row, $pawn->getXCoordinate());
                $this->assertEquals($i % ChessBoard::MAX_BOARD_WIDTH, $pawn->getYCoordinate());
            } else {
                $this->assertEquals(-1, $pawn->getXCoordinate());
                $this->assertEquals(-1, $pawn->getYCoordinate());
            }
        }
        
        /** adding one more WHITE just to be sure, that limit was counted for proper color */
        $pawn = new Pawn(PieceColorEnum::WHITE());
        $this->_testSubject->add($pawn, 0, 0, PieceColorEnum::WHITE());
        $this->assertEquals(-1, $pawn->getXCoordinate());
        $this->assertEquals(-1, $pawn->getYCoordinate());
        
        /** "BLACK" loop */
        for ($i = 0; $i < 10; $i++) {
            /** intentionally here is WHITE and in "add" method is BLACK. It helps to check, if color of piece is set before checking limit for it */
            $pawn = new Pawn(PieceColorEnum::WHITE());
            $row = intval($i / ChessBoard::MAX_BOARD_WIDTH);
            /** X coordinate must be different than in "WHITE" loop, to avoid adding on occupied positions. For that reason here is 4+$row */
            $this->_testSubject->add($pawn, 4 + $row, $i % ChessBoard::MAX_BOARD_WIDTH, PieceColorEnum::BLACK());
            if ($row < 1) {
                $this->assertEquals(4 + $row, $pawn->getXCoordinate());
                $this->assertEquals($i % ChessBoard::MAX_BOARD_WIDTH, $pawn->getYCoordinate());
            } else {
                $this->assertEquals(-1, $pawn->getXCoordinate());
                $this->assertEquals(-1, $pawn->getYCoordinate());
            }
        }
    }
    
    public function testCheck_Different_Pieces_Limit()
    {
        /** Pawns */
        $pawn = new Pawn(PieceColorEnum::BLACK());
        $pawnsLimit = $pawn->oneColorPieceQuantityLimit();
        
        for ($i = 0; ; $i++)
        {
            $pawn = new Pawn(PieceColorEnum::BLACK());
            if ($i == $pawnsLimit)
            {
                $this->_testSubject->add($pawn, 1, 1, PieceColorEnum::BLACK());
                $this->assertEquals(-1, $pawn->getXCoordinate());
                $this->assertEquals(-1, $pawn->getYCoordinate());
                break;
            }
            $this->_testSubject->add($pawn, 0, $i, PieceColorEnum::BLACK());
            $this->assertEquals(0, $pawn->getXCoordinate());
            $this->assertEquals($i, $pawn->getYCoordinate());
        }
        
        /** @todo create tests for other pieces types, when they will be implemented */
    }
    
    public function testCheck_Add_Stores_Piece_On_ChessBoard()
	{
		$piece = new Pawn(PieceColorEnum::BLACK());
        $this->_testSubject->add($piece, 1, 4, PieceColorEnum::BLACK());
        $resultPiece = $this->_testSubject->getPieceOnPosition(1, 4);
		$this->assertEquals($resultPiece, $piece);
    }

}