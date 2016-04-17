<?php

namespace LogicNow;

class ChessBoard
{

    const MAX_BOARD_WIDTH = 7;
    const MAX_BOARD_HEIGHT = 7;

    private $_pieces;
	
	/** @var int */
	private $_blackPawnsNum;
	/** @var int */
	private $_whitePawnsNum;

    public function __construct()
    {
        $this->_pieces = array_fill(0, self::MAX_BOARD_WIDTH, array_fill(0, self::MAX_BOARD_HEIGHT, 0));
		$this->_blackPawnsNum = 0;
		$this->_whitePawnsNum = 0;
    }

    public function add(Pawn $pawn, $_xCoordinate, $_yCoordinate, PieceColorEnum $pieceColor)
    {
		$pawn->setPieceColor($pieceColor);
		if (self::isPawnLimitReached($pieceColor) or !self::isLegalBoardPosition($_xCoordinate, $_yCoordinate))
		{
			$pawn->setXCoordinate(-1);
			$pawn->setYCoordinate(-1);
			return;
		}
		
		$pawn->setXCoordinate($_xCoordinate);
		$pawn->setYCoordinate($_yCoordinate);
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
		
		/** @todo check if positions is not already occupied */
		
		return true;
    }
	
	/** @return: boolean */
    public function isPawnLimitReached(PieceColorEnum $pieceColor)
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