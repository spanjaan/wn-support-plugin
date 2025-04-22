<?php

namespace SpAnjaan\Support\Classes;

use Hashids\Hashids;
use Validator;
use Winter\Storm\Support\Facades\Config;
use Winter\Storm\Validation\ValidationException;

/**
 * Class SupportHelpers
 * @package SpAnjaan\Support\Classes
 */
class SupportHelpers
{
    private Hashids $hashids;
    
    private const HASHID_LENGTH = 8;
    private const HASHID_ALPHABET = 'ABCDEF1234567890';

    public function __construct()
    {
        $this->hashids = new Hashids(Config::get('app.key'), self::HASHID_LENGTH, self::HASHID_ALPHABET);
    }

    /**
     * Validates ticket fields
     *
     * @param array $data
     *
     * @throws ValidationException
     */
    public function validateTicket(array $data): void
    {
        $rules = [
            'topic'   => 'required|string|max:255',
            'content' => 'required|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Generates ticket Hash ID
     *
     * @param int $id
     *
     * @return string
     */
    public function generateHashId(int $id): string
    {
        return $this->hashids->encode($id);
    }

    /**
     * Placeholder method to handle new ticket notifications.
     *
     * @param string $hashId
     *
     * @return void
     */
    public function newTicketHandler(string $hashId): void
    {
        // Implementation for notifying users about a new ticket can go here.
    }
}
