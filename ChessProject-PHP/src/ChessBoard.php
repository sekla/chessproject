<?php

namespace LogicNow;

class ChessBoard
{
    /** treated as max X index */
    const MAX_BOARD_WIDTH = 7;
    /** treated as max Y index */
    const MAX_BOARD_HEIGHT = 7;

	/** map of free and occupied positions: 0 = free; 1 = occupied */
    private $_fields;
	const POSITION_FREE = 0;
	const POSITION_OCCUPIED = 1;
	
	/** array which stores current number of pawns on the board, separately for each color */
	private $_pawnsCount;

    public function __construct()
    {
        $this->_fields = array_fill(0, self::MAX_BOARD_WIDTH+1, array_fill(0, self::MAX_BOARD_HEIGHT+1, self::POSITION_FREE));
		$this->_pawnsCount[PieceColorEnum::WHITE()->toString()] = 0;
		$this->_pawnsCount[PieceColorEnum::BLACK()->toString()] = 0;
    }

    public function add(Pawn $pawn, $xCoordinate, $yCoordinate, PieceColorEnum $pieceColor)
    {
		$pawn->setPieceColor($pieceColor);

		if (self::isPieceLimitReached($pawn)
			or !self::isLegalBoardPosition($xCoordinate, $yCoordinate)
			or self::isPositionOccupied($xCoordinate, $yCoordinate))
		{
			$pawn->setXCoordinate(-1);
			$pawn->setYCoordinate(-1);
			return;
		}

        $pawn->setChessBoard($this);
		$pawn->setXCoordinate($xCoordinate);
		$pawn->setYCoordinate($yCoordinate);
		$this->_fields[$xCoordinate][$yCoordinate] = self::POSITION_OCCUPIED;
		$this->_pawnsCount[$pawn->getPieceColor()->toString()]++;
    }

    /** @return: boolean */
    public function isLegalBoardPosition($xCoordinate, $yCoordinate)
    {
		if ($xCoordinate > self::MAX_BOARD_WIDTH or $xCoordinate < 0 or $yCoordinate > self::MAX_BOARD_HEIGHT or $yCoordinate < 0)
		{
			return false;
		}
		
		return true;
    }
	
	/** @return: boolean */
    public function isPositionOccupied($xCoordinate, $yCoordinate)
    {
		return self::POSITION_OCCUPIED == $this->_fields[$xCoordinate][$yCoordinate];
    }
	
	/** validity of this move is not checked here */
	public function moveOccupiedPosition($xCoordinateOld, $yCoordinateOld, $xCoordinateNew, $yCoordinateNew)
	{
		/** freeOccupiedPosition is introduced, to make implementation of En passant ("in passing") capture easier */
		$this->freeOccupiedPosition($xCoordinateOld, $yCoordinateOld);
		$this->_fields[$xCoordinateNew][$yCoordinateNew] = self::POSITION_OCCUPIED;
	}
	
	public function freeOccupiedPosition($xCoordinate, $yCoordinate)
	{
		$this->_fields[$xCoordinate][$yCoordinate] = self::POSITION_FREE;
	}
	
	/** @return: boolean */
    private function isPieceLimitReached(AbstractPiece $piece)
    {
		if (!$piece instanceof Pawn)
		{
			throw new \Exception("Need to implement handling other pieces types in ChessBoard.isPieceLimitReached");
		}
        
		return $this->_pawnsCount[$piece->getPieceColor()->toString()] >= $piece->oneColorPieceQuantityLimit();
    }
}