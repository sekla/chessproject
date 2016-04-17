<?php

namespace LogicNow;


abstract class AbstractPiece
{
    /** @var PieceColorEnum */
    private $_pieceColorEnum;
    /** @var  ChessBoard */
    private $_chessBoard;
    /** @var  int */
    private $_xCoordinate;
    /** @var  int */
    private $_yCoordinate;

	/** @todo constructor should set color and coordinates or nothing. Now setting color is done here and in ChessBoard.add method (where can be also changed)
	 *	I cannot fix it now, because I should not change acceptance tests.
	 */
    public function __construct(PieceColorEnum $pieceColorEnum) 
    {
        $this->_pieceColorEnum = $pieceColorEnum;
    }

	abstract public function move(MovementTypeEnum $movementTypeEnum, $newX, $newY);
	
	/** @return int */
	abstract public function oneColorPieceQuantityLimit();
	
    public function getChessBoard()
    {
        return $this->_chessBoard;
    }

    public function setChessBoard(ChessBoard $chessBoard)
    {
        $this->_chessBoard = $chessBoard;
    }

    /** @return int */
    public function getXCoordinate()
    {
        return $this->_xCoordinate;
    }

    /** @var int */
    public function setXCoordinate($value)
    {
        $this->_xCoordinate = $value;
    }

    /** @return int */
    public function getYCoordinate()
    {
        return $this->_yCoordinate;
    }

    /** @var int */
    public function setYCoordinate($value)
    {
        $this->_yCoordinate = $value;
    }

    public function getPieceColor()
    {
        return $this->_pieceColorEnum;
    }

    public function setPieceColor(PieceColorEnum $value)
    {
        $this->_pieceColorEnum = $value;
    }

    public function toString()
    {
        return $this->currentPositionAsString();
    }

    protected function currentPositionAsString()
    {
		$result = "Type: " . $this->pieceTypeAsString() . PHP_EOL;
        $result .= "Current X: " . $this->getXCoordinate() . PHP_EOL;
        $result .= "Current Y: " . $this->getYCoordinate() . PHP_EOL;
        $result .= "Color: " . $this->getPieceColor()->toString();
        return $result;
    }
	
	protected function pieceTypeAsString()
	{
		return "Piece";
	}

}