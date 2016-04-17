<?php

namespace LogicNow;


class Pawn
{
	/** @todo this should be always the same as ChessBoard::MAX_BOARD_WIDTH. Need to think, how to make sure about it automatically */
	const EACH_COLOR_PAWNS_LIMIT = 7;

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

    public function getChesssBoard()
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

    public function move(MovementTypeEnum $movementTypeEnum, $newX, $newY)
    {
		if(MovementTypeEnum::CAPTURE() == $movementTypeEnum)
		{
			throw new \Exception("Need to implement movement CAPTURE in Pawn.move()");
		}
		
		/** @todo check if move is valid for pawns */
		if (!$this->getChesssBoard()->isLegalBoardPosition($newX, $newY) or $this->getChesssBoard()->isPositionOccupied($newX, $newY))
		{
			return;
		}
		
		$this->setXCoordinate($newX);
		$this->setYCoordinate($newY);
		
		/** @todo change position in ChessBoard._fields */
    }

    public function toString()
    {
        return $this->currentPositionAsString();
    }

    protected function currentPositionAsString()
    {
        $result = "Current X: " . $this->_xCoordinate . PHP_EOL;
        $result .= "Current Y: " . $this->_yCoordinate . PHP_EOL;
        $result .= "Piece Color: " . $this->_pieceColorEnum->toString();
        return $result;
    }

}