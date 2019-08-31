<?php namespace obsession\Domain\Files\Medias\Repositories;

use Illuminate\Support\Facades\
{
    File,
    Response
};
use Spatie\MediaLibrary\HasMedia\HasMedia;
use obsession\App\Traits\SecurityHashTrait;
use obsession\Infrastructure\Contracts\
{
    Request\RequestAbstract,
    Repositories\RepositoryEloquentAbstract
};
use obsession\Domain\Files\Medias\
{
    Media,
    Repositories\MediasRepository
};

class MediasRepositoryEloquent extends RepositoryEloquentAbstract implements MediasRepository
{

    use SecurityHashTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Media::class;
    }

    /**
     * Attach the file to post, the media library will now able to
     * manage the file.
     *
     * @param HasMedia $entity
     * @param string $newFilePath
     * @param string $collection
     * @param string $diskName
     * @param array $collectionFilters
     *
     * @return HasMedia
     */
    public function attachNewMediaToEntityAndDeletePreviousOne(
        HasMedia $entity,
        $newFilePath,
        $collection,
        $diskName = '',
        $collectionFilters = []
    ) {
        $entity
            ->getMedia($collection, $collectionFilters)
            ->each(function ($media) use ($entity) {
                // HasMediaTrait
                $entity->deleteMedia($media->id);
            });

        $entity
            ->addMedia($newFilePath)
            ->toMediaCollection($collection, $diskName);

        return $entity;
    }

    /**
     * @param $path
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function streamPublicDocument($path)
    {
        $path = storage_path('app/public/'.$path);

        if (File::exists($path)) {

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        throw new \Exception();
    }

    /**
     * @param $path
     */
    public function outputPublicThumbnails($path)
    {
        $server = \League\Glide\ServerFactory::create([
            'source' => app('filesystem')
                ->disk('public')
                ->getDriver(),
            'cache' => storage_path('app/thumbnails'),
        ]);

        $server->outputImage($path, \Illuminate\Support\Facades\Input::query());
    }

    /**
     * @param string $hash
     *
     * @seealso https://laracasts.com/discuss/channels/general-discussion/opening-files-online-using-laravel
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function streamPrivateDocument($hash = '')
    {
        $data = $this->readHash($hash);

        $raw_media_file = $this->find($data['id']);

        if (File::isFile($raw_media_file->getPath())) {
            $path_parts = pathinfo($raw_media_file->getPath());
            $extension = $path_parts['extension'];
            $content_types = config('content.types');

            $file_path = File::get($raw_media_file->getPath());
            $response = Response::make($file_path, 200);

            // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
            $response->header('Content-Type', $content_types[$extension]);
            $response->header(
                'Content-disposition',
                'filename="'.$raw_media_file->file_name.'"'
            );

            return $response;
        }

        throw new \Exception();
    }
}
