<?php

namespace LogicNow;

class ChessBoard
{

    const MAX_BOARD_WIDTH = 7;
    const MAX_BOARD_HEIGHT = 7;

	/** map of free and occupied positions: 0 = free; 1 = occupied */
    private $_fields;
	const POSITION_FREE = 0;
	const POSITION_OCCUPIED = 1;
	
	/** @var int */
	private $_blackPawnsNum;
	/** @var int */
	private $_whitePawnsNum;

    public function __construct()
    {
        $this->_fields = array_fill(0, self::MAX_BOARD_WIDTH, array_fill(0, self::MAX_BOARD_HEIGHT, self::POSITION_FREE));
		$this->_blackPawnsNum = 0;
		$this->_whitePawnsNum = 0;
    }

    public function add(Pawn $pawn, $_xCoordinate, $_yCoordinate, PieceColorEnum $pieceColor)
    {
		if (self::isPawnsLimitReached($pieceColor)
			or !self::isLegalBoardPosition($_xCoordinate, $_yCoordinate)
			or self::isPositionOccupied($_xCoordinate, $_yCoordinate))
		{
			$pawn->setXCoordinate(-1);
			$pawn->setYCoordinate(-1);
			return;
		}
		
		$pawn->setPieceColor($pieceColor);
		$pawn->setChessBoard($this);
		$pawn->setXCoordinate($_xCoordinate);
		$pawn->setYCoordinate($_yCoordinate);
		$this->_fields[$_xCoordinate][$_yCoordinate] = self::POSITION_OCCUPIED;
		if(PieceColorEnum::WHITE() == $pieceColor)
		{
			$this->_whitePawnsNum++;
		}
		else
		{
			$this->_blackPawnsNum++;
		}
    }

    /** @return: boolean */
    public function isLegalBoardPosition($_xCoordinate, $_yCoordinate)
    {
		if ($_xCoordinate >= self::MAX_BOARD_WIDTH or $_xCoordinate < 0 or $_yCoordinate >= self::MAX_BOARD_HEIGHT or $_yCoordinate < 0)
		{
			return false;
		}
		
		return true;
    }
	
	/** @return: boolean */
    public function isPositionOccupied($_xCoordinate, $_yCoordinate)
    {
		return self::POSITION_OCCUPIED == $this->_fields[$_xCoordinate][$_yCoordinate];
    }
	
	/** @return: boolean */
    private function isPawnsLimitReached(PieceColorEnum $pieceColor)
    {
        if(PieceColorEnum::WHITE() == $pieceColor and $this->_whitePawnsNum >= Pawn::EACH_COLOR_PAWNS_LIMIT)
		{
			return true;
		}
		else if ($this->_blackPawnsNum >= Pawn::EACH_COLOR_PAWNS_LIMIT)
		{
			return true;
		}
		
		return false;
    }
}