<?php
class Storage
{
    private $dataFile;
    private $data;
    public function __construct($dataFile = 'datas.json')
    {
        $this->dataFile = $dataFile;

        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode(['cards' => [], 'users' => []]));
        }

        $this->data = json_decode(file_get_contents($this->dataFile), true);
    }

    public function __serialize(): array
    {
        return ['dataFile' => $this->dataFile, 'data' => $this->data];
    }

    public function __unserialize(array $data): void
    {
        $this->dataFile = $data['dataFile'];
        $this->data = $data['data'];
    }

    private function saveData()
    {
        file_put_contents($this->dataFile, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    public function getAllCards()
    {
        return $this->data['cards'];
    }

    public function getCardById($cardId)
    {
        return $this->data['cards'][$cardId] ?? null;
    }

    public function getUserCards($userId){
        $userCards = $this->data['users'][$userId]['cards'] ?? [];

        $result = [];
        foreach ($userCards as $cardId) {
            $result[$cardId] = $this->getCardById($cardId);
        }

        return $result;
    }

    public function getAllUsers()
    {
        return $this->data['users'];
    }

    public function getUserById($userId)
    {
        return $this->data['users'][$userId];
    }

    public function saveUser($user)
    {
        $this->data['users'][] = $user;
        $this->saveData();
    }

    public function saveCard($card)
    {
        $this->data['cards'][] = $card;
        $this->saveData();
    }

    public function buyCard($userId, $cardId)
    {
        $user = $this->getUserById($userId);

        if (!$user) {
            return "A felhasználó nem található.";
        }

        $card = $this->data['cards'][$cardId];

        if ($user['money'] < $card['price']) {
            return "Nincs elegendő pénzed a kártya megvásárlásához!";
        }

        if (count($user['cards']) >= 5) {
            return "Elérted a kártyalimitet, nem vásárolhatsz több kártyát!";
        }

        $this->data['users'][$userId]['money'] -= $card['price'];
        $this->data['users'][$userId]['cards'][] = $cardId;
        unset($this->data['users'][0]['cards'][$cardId]);

        $this->saveData();

        $_SESSION['user'] = $this->data['users'][$userId];

        return "Kártya sikeresen megvásárolva!";
    }
}
?>