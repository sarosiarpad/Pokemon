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

    public function getUserCards($user){
        $userCards = $this->data['users'][$user]['cards'] ?? [];

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

    public function getUserByUsername($username)
    {
        foreach ($this->data['users'] as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        return null;
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

    public function buyCard($username, $cardId)
    {
        $user = $this->getUserByUsername($username);

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

        $userKey = array_search($user, $this->data['users']);
        $this->data['users'][$userKey]['money'] -= $card['price'];
        $this->data['users'][$userKey]['cards'][] = $cardId;

        $this->saveData();

        $_SESSION['user'] = $this->data['users'][$userKey];

        return "Kártya sikeresen megvásárolva!";
    }
}
?>