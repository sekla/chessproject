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

    public function move(MovementTypeEnum $movementTypeEnum, $newX, $newY)
    {
		if(MovementTypeEnum::CAPTURE() == $movementTypeEnum)
		{
			/** @todo remember to use ChessBoard.freeOccupiedPosition for En passant ("in passing") capture */
			throw new \Exception("Need to implement movement CAPTURE in Pawn.move()");
		}
		
		if (!$this->getChessBoard()->isLegalBoardPosition($newX, $newY)
			or $this->getChessBoard()->isPositionOccupied($newX, $newY)
		    or !$this->checkMoveValidity($movementTypeEnum, $newX, $newY))
		{
			/** do nothing if move is not valid */
			return;
		}

		$this->getChessBoard()->moveOccupiedPosition($this->getXCoordinate(), $this->getYCoordinate(), $newX, $newY);
		$this->setXCoordinate($newX);
		$this->setYCoordinate($newY);
    }

    public function toString()
    {
        return $this->currentPositionAsString();
    }
	
	/** @return: boolean */
	private function checkMoveValidity(MovementTypeEnum $movementTypeEnum, $newX, $newY)
	{
		if(MovementTypeEnum::CAPTURE() == $movementTypeEnum)
		{
			if (!$this->getChessBoard()->isPositionOccupied($newX, $newY))
			{
				return false;
			}
			
			/** @todo check if a piece on ($newX, $newY) has opposite color. */
			
			if ($this->getPieceColor() == PieceColorEnum::WHITE())
			{
				/** whites go up the board */
				$isUpRightCapture = ($newX == ($this->getXCoordinate() + 1) and $newY == ($this->getYCoordinate() + 1));
				$isUpLeftCapture = ($newX == ($this->getXCoordinate() - 1) and $newY == ($this->getYCoordinate() + 1));
				return $isUpRightCapture or isUpLeftCapture;
			}
			
			/** blacks go down the board */
			$isDownRightCapture = ($newX == ($this->getXCoordinate() + 1) and $newY == ($this->getYCoordinate() - 1));
			$isDownLeftCapture = ($newX == ($this->getXCoordinate() - 1) and $newY == ($this->getYCoordinate() - 1));
			return $isDownRightCapture or isDownLeftCapture;
		}
		
		/** for now the only valid move for pawns is moving 1 space forward (toward opponents board side) */
		/** @todo remember that pawn can move also 2 spaces on its first move. Need to be implemented! */
		if ($this->getPieceColor() == PieceColorEnum::WHITE())
		{
			/** whites go up the board */
			return ($newX == $this->getXCoordinate() and $newY == ($this->getYCoordinate() + 1));
		}
		
		/** blacks go down the board */
		return ($newX == $this->getXCoordinate() and $newY == ($this->getYCoordinate() - 1));
	}

    protected function currentPositionAsString()
    {
        $result = "Current X: " . $this->_xCoordinate . PHP_EOL;
        $result .= "Current Y: " . $this->_yCoordinate . PHP_EOL;
        $result .= "Piece Color: " . $this->_pieceColorEnum->toString();
        return $result;
    }

}