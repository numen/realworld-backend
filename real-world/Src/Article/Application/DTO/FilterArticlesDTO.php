<?php
declare(strict_types=1);

namespace Article\Application\DTO;

use Illuminate\Support\Facades\Log;

final class FilterArticlesDTO
{
    protected const FILTER_LIMIT = 20;

    protected const FILTER_OFFSET = 0;

	private ?string $tag;
	private ?string $author;
	private ?string $favorited;
	private int $limit;
	private int $offset;

	public function __construct(
		?string $tag = null,
		?string $author = null,
		?string $favorited = null,
		int $limit = 20,
		int $offset = 0
	)
	{
		$this->tag = $tag;
		$this->author = $author;
		$this->favorited = $favorited;
		$this->limit = $limit;
		$this->offset = $offset;
	}

    public static function fromArray(array $data): FilterArticlesDTO
    {
        return new self(
            $data['tag'] ?? null,
            $data['author'] ?? null,
            $data['favorited'] ?? null,
            (int) ($data['limit'] ?? static::FILTER_LIMIT),
            (int) ($data['offset'] ?? static::FILTER_OFFSET)
        );
    }

	public function tag(): ?string
	{
		return $this->tag;
	}

	public function author(): ?string
	{
		return $this->author;
	}

	public function favorited(): ?string
	{
		return $this->favorited;
	}

	public function limit(): int
	{
		return $this->limit;
	}

	public function offset(): int
	{
		return $this->offset;
    }

    public function toArray(): array
    {
        $data = array();

        if( $this->tag() ) {
            $data['tag'] = $this->tag;
        }
        if( $this->author() ) {
            $data['author'] = $this->author;
        }
        if( $this->favorited() ) {
            $data['favorited'] = $this->favorited;
        }

        $data['limit'] = $this->limit;
        $data['offset'] = $this->offset;

        return $data;
    }

}
