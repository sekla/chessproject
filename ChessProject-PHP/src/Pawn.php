<?php

namespace LogicNow;


class Pawn extends AbstractPiece
{
    public function __construct(PieceColorEnum $pieceColorEnum) 
    {
        parent::__construct($pieceColorEnum);
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
	
	public function oneColorPieceQuantityLimit()
	{
		return ChessBoard::MAX_BOARD_WIDTH+1;
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

    protected function pieceTypeAsString()
    {
        return "Pawn";
    }

}