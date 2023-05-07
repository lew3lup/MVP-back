<?php

namespace App\Service;

use App\Entity\Backer;
use App\Entity\Game;
use App\Entity\GameAdmin;
use App\Entity\GameBacker;
use App\Entity\GameCategory;
use App\Entity\GameChain;
use App\Entity\Image;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Exception\BadRequestException;
use App\Repository\BackerRepository;
use App\Repository\CategoryRepository;
use App\Repository\ChainRepository;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GameService
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var GameRepository */
    private $gameRepo;
    /** @var CategoryRepository */
    private $categoryRepo;
    /** @var ChainRepository */
    private $chainRepo;
    /** @var BackerRepository */
    private $backerRepo;
    /** @var DescriptionService */
    private $descriptionService;
    /** @var ImageService */
    private $imageService;

    /**
     * GameService constructor.
     * @param EntityManagerInterface $em
     * @param GameRepository $gameRepo
     * @param CategoryRepository $categoryRepo
     * @param ChainRepository $chainRepo
     * @param BackerRepository $backerRepo
     * @param DescriptionService $descriptionService
     * @param ImageService $imageService
     */
    public function __construct(
        EntityManagerInterface $em,
        GameRepository $gameRepo,
        CategoryRepository $categoryRepo,
        ChainRepository $chainRepo,
        BackerRepository $backerRepo,
        DescriptionService $descriptionService,
        ImageService $imageService
    ) {
        $this->em = $em;
        $this->gameRepo = $gameRepo;
        $this->categoryRepo = $categoryRepo;
        $this->chainRepo = $chainRepo;
        $this->backerRepo = $backerRepo;
        $this->descriptionService = $descriptionService;
        $this->imageService = $imageService;
    }

    /**
     * @return array
     */
    public function getGameFilters(): array
    {
        $gamesCategories = $this->categoryRepo->findAll();
        $gamesChains = $this->chainRepo->findAll();
        $gamesBackers = $this->backerRepo->findAll();
        $activeGamesCategories = [];
        $activeGamesChains = [];
        $activeGamesBackers = [];
        foreach ($gamesCategories as $category) {
            if ($category->getGameCategories()) {
                foreach ($category->getGameCategories() as $gameCategory) {
                    if ($gameCategory->getGame()->isActive()) {
                        $activeGamesCategories[] = $category;
                        break;
                    }
                }
            }
        }
        foreach ($gamesChains as $chain) {
            if ($chain->getGameChains()) {
                foreach ($chain->getGameChains() as $gameChain) {
                    if ($gameChain->getGame()->isActive()) {
                        $activeGamesChains[] = $chain;
                        break;
                    }
                }
            }
        }
        foreach ($gamesBackers as $backer) {
            if ($backer->getGameBackers()) {
                foreach ($backer->getGameBackers() as $gameBacker) {
                    if ($gameBacker->getGame()->isActive()) {
                        $activeGamesBackers[] = $backer;
                        break;
                    }
                }
            }
        }
        return [
            'gamesCategories' => $gamesCategories,
            'gamesChains' => $gamesChains,
            'gamesBackers' => $gamesBackers,
            'activeGamesCategories' => $activeGamesCategories,
            'activeGamesChains' => $activeGamesChains,
            'activeGamesBackers' => $activeGamesBackers,
        ];
    }

    /**
     * @param int $id
     * @param int $adminId
     * @return Game
     */
    public function getByIdAndAdminId(int $id, int $adminId): Game
    {
        $game = $this->gameRepo->findOneByIdAndAdminId($id, $adminId);
        if (!$game) {
            throw new NotFoundException();
        }
        return $game;
    }

    /**
     * @param User $user
     * @param array $data
     * @return Game
     */
    public function addGame(User $user, array $data): Game
    {
        $game = $this->setData(new Game(), $data)->setAddedAt(new DateTimeImmutable());
        $gameAdmin = (new GameAdmin())->setGame($game)->setUser($user);
        $this->em->persist($game);
        $this->em->persist($gameAdmin);
        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function updateGame(Game $game, array $data): Game
    {
        foreach ($game->getGameCategories() as $gameCategory) {
            $this->em->remove($gameCategory);
        }
        foreach ($game->getGameChains() as $gameChain) {
            $this->em->remove($gameChain);
        }
        foreach ($game->getGameBackers() as $gameBacker) {
            $this->em->remove($gameBacker);
        }
        return $this->setData($game, $data);
    }

    /**
     * @param Game $game
     * @param UploadedFile $logo
     * @return Game
     */
    public function setGameLogo(Game $game, UploadedFile $logo): Game
    {
        $this->imageService->validateImageFile($logo);
        //ToDo: ресайз файла
        return $game->setLogo($this->imageService->uploadImageFile($this->getGameLogoPath($game), $logo));
    }

    /**
     * @param Game $game
     * @return Game
     */
    public function removeGameLogo(Game $game): Game
    {
        $this->imageService->removeImageFile($this->getGameLogoPath($game));
        return $game->setLogo(null);
    }

    /**
     * @param Game $game
     * @param UploadedFile $file
     * @return Game
     */
    public function addGameImage(Game $game, UploadedFile $file): Game
    {
        if (count($game->getImages()) >= 5) {
            throw new BadRequestException();
        }
        $this->imageService->validateImageFile($file);
        //ToDo: ресайз файла
        $image = (new Image())->setGame($game);
        $this->imageService->uploadImage($image, $this->getGameImagePath($game), $file);
        $this->em->persist($image);
        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    private function setData(Game $game, array $data): Game
    {
        if (
            empty($data['path']) || iconv_strlen($data['path']) > 30 ||
            !preg_match('/^[a-z0-9\-_]+$/', $data['path'])
        ) {
            throw new BadRequestException('INVALID_PATH');
        }
        if (empty($data['homePage']) || !filter_var($data['homePage'], FILTER_VALIDATE_URL)) {
            throw new BadRequestException('INVALID_HOME_PAGE');
        }
        if (!empty($data['twitter']) && (
            !filter_var($data['twitter'], FILTER_VALIDATE_URL) ||
            strpos($data['twitter'], 'https://twitter.com/') !== 0
        )) {
            throw new BadRequestException('INVALID_TWITTER_LINK');
        }
        //ToDo: возможно, нужна дополнительная валидация ссылки на дискорд
        if (!empty($data['discord']) && !filter_var($data['discord'], FILTER_VALIDATE_URL)) {
            throw new BadRequestException('INVALID_DISCORD_LINK');
        }
        if (!empty($data['telegram']) && (
            !filter_var($data['telegram'], FILTER_VALIDATE_URL) ||
            strpos($data['telegram'], 'https://t.me/') !== 0
        )) {
            throw new BadRequestException('INVALID_TELEGRAM_LINK');
        }
        if (!empty($data['coinMarketCap']) && (
            !filter_var($data['coinMarketCap'], FILTER_VALIDATE_URL) ||
            strpos($data['coinMarketCap'], 'https://coinmarketcap.com/currencies/')
        )) {
            throw new BadRequestException('INVALID_COIN_MARKET_CAP_LINK');
        }
        if (
            !isset($data['active']) ||
            !isset($data['categories']) || !is_array($data['categories']) ||
            !isset($data['chains']) || !is_array($data['chains']) ||
            !isset($data['backers']) || !is_array($data['backers'])
        ) {
            throw new BadRequestException();
        }
        $this->descriptionService->setData($game, $data, 80, 500);
        if (!empty($data['categories'])) {
            $categories = $this->categoryRepo->findByIds($data['categories']);
            foreach ($categories as $category) {
                $gameCategory = (new GameCategory())->setGame($game)->setCategory($category);
                $this->em->persist($gameCategory);
            }
        }
        if (!empty($data['chains'])) {
            $chains = $this->chainRepo->findByIds($data['chains']);
            foreach ($chains as $chain) {
                $gameChain = (new GameChain())->setGame($game)->setChain($chain);
                $this->em->persist($gameChain);
            }
        }
        if (!empty($data['backers'])) {
            $backers = $this->backerRepo->findByNames($data['backers']);
            $existedBackersNames = [];
            foreach ($backers as $backer) {
                $gameBacker = (new GameBacker())->setGame($game)->setBacker($backer);
                $this->em->persist($gameBacker);
                $existedBackersNames[] = strtolower($backer->getName());
            }
            foreach ($data['backers'] as $backerName) {
                if (!in_array(strtolower($backerName), $existedBackersNames)) {
                    $backer = (new Backer())->setName($backerName);
                    $gameBacker = (new GameBacker())->setGame($game)->setBacker($backer);
                    $this->em->persist($backer);
                    $this->em->persist($gameBacker);
                }
            }
        }
        return $game
            ->setPath($data['path'])
            ->setHomePage($data['homePage'])
            ->setTwitter(!empty($data['twitter']) ? $data['twitter'] : null)
            ->setDiscord(!empty($data['discord']) ? $data['discord'] : null)
            ->setTelegram(!empty($data['telegram']) ? $data['telegram'] : null)
            ->setCoinMarketCap(!empty($data['coinMarketCap']) ? $data['coinMarketCap'] : null)
            ->setActive($data['active']);
    }

    /**
     * @param Game $game
     * @return string
     */
    private function getGameLogoPath(Game $game): string
    {
        return $this->getGameImagesPath($game) . 'logo.jpg';
    }

    /**
     * @param Game $game
     * @return string
     */
    private function getGameImagePath(Game $game): string
    {
        return $this->getGameImagesPath($game) . time() . '_' . rand(1000, 9999) . '.jpg';
    }

    /**
     * @param Game $game
     * @return string
     */
    private function getGameImagesPath(Game $game): string
    {
        return 'games/' . $game->getId() . '/';
    }
}