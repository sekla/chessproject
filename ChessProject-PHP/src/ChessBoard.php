<?php

namespace LogicNow;

class ChessBoard
{

    const MAX_BOARD_WIDTH = 7;
    const MAX_BOARD_HEIGHT = 7;

	/** @var int[][] $_fields Map of free and occupied positions: free = -1; occupied = (piece id in $_pieces array) */
    private $_fields;
    
    /** value indicating free position in $_fields map */
	const POSITION_FREE = -1;
    
    /** @var AbstractPiece[] $_pieces Array of pieces added to this ChessBoard. NULL-initialized size self::MAX_BOARD_WIDTH * 4. Can be expanded on demand */
    private $_pieces;
    
    /** @var int $_nextFreePieceId The next free id, which can be given to newly added piece */
    private $_nextFreePieceId;
	
	/** @var int[][] $_pawnsCount Array which stores current number of pawns on the board, separately for each color */
	private $_pawnsCount;

    public function __construct()
    {
        $this->_fields = array_fill(0, self::MAX_BOARD_WIDTH, array_fill(0, self::MAX_BOARD_HEIGHT, self::POSITION_FREE));
		$this->_pawnsCount[PieceColorEnum::WHITE()->toString()] = 0;
		$this->_pawnsCount[PieceColorEnum::BLACK()->toString()] = 0;
        $this->_nextFreePieceId = 0;
        $this->_pieces = array_fill(0, self::MAX_BOARD_WIDTH * 4, NULL);
    }

    public function add(AbstractPiece $piece, $xCoordinate, $yCoordinate, PieceColorEnum $pieceColor)
    {
		$piece->setPieceColor($pieceColor);

		if (self::isPieceLimitReached($piece)
			or !self::isLegalBoardPosition($xCoordinate, $yCoordinate)
			or self::isPositionOccupied($xCoordinate, $yCoordinate))
		{
			$piece->setXCoordinate(-1);
			$piece->setYCoordinate(-1);
			return;
		}

        $piece->setChessBoard($this);
		$piece->setXCoordinate($xCoordinate);
		$piece->setYCoordinate($yCoordinate);
        
		$this->_fields[$xCoordinate][$yCoordinate] = $this->_nextFreePieceId;
        $this->_pieces[$this->_nextFreePieceId++] = $piece;
		$this->_pawnsCount[$piece->getPieceColor()->toString()]++;
    }

    /** @return: boolean */
    public function isLegalBoardPosition($xCoordinate, $yCoordinate)
    {
		if ($xCoordinate >= self::MAX_BOARD_WIDTH or $xCoordinate < 0 or $yCoordinate >= self::MAX_BOARD_HEIGHT or $yCoordinate < 0)
		{
			return false;
		}
		
		return true;
    }
	
	/** @return: boolean */
    public function isPositionOccupied($xCoordinate, $yCoordinate)
    {
		return self::POSITION_FREE != $this->_fields[$xCoordinate][$yCoordinate];
    }
	
	/** validity of this move is not checked here */
	public function moveOccupiedPosition($xCoordinateOld, $yCoordinateOld, $xCoordinateNew, $yCoordinateNew)
	{
        $this->_fields[$xCoordinateNew][$yCoordinateNew] = $this->_fields[$xCoordinateOld][$yCoordinateOld];
		/** freeOccupiedPosition is introduced, to make implementation of En passant ("in passing") capture easier */
		$this->freeOccupiedPosition($xCoordinateOld, $yCoordinateOld);
	}
	
	public function freeOccupiedPosition($xCoordinate, $yCoordinate)
	{
		$this->_fields[$xCoordinate][$yCoordinate] = self::POSITION_FREE;
	}
    
    /** @return AbstractPiece|NULL */
    public function getPieceOnPosition($xCoordinate, $yCoordinate)
    {
        if (!$this->isPositionOccupied($xCoordinate, $yCoordinate))
        {
            return NULL;
        }
        
        $pieceId = $this->_fields[$xCoordinate][$yCoordinate];
        return $this->_pieces[$pieceId];
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