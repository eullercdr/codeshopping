<?php
declare(strict_types=1);

use CodeShopping\Models\Product;
use CodeShopping\Models\ProductPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ProductPhotosTableSeeder extends Seeder
{
    /** @var Collection */
    private $allFakerPhotos;
    private $fakerPhotosPath = 'app/faker/product_photos';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->allFakerPhotos = $this->getFakerPhotos();
        /** @var \Illuminate\Database\Eloquent\Collection $products */
        $products = Product::all();
        $this->deleteAllPhotoInProductsPath();
        $self = $this;
        $products->each(function ($product) use ($self) {
            $self->createPhotoDir($product);
            $self->createPhotosModels($product);
        });
    }

    /**
     * Recupera todas as fotos que estão no faker_path
     * @return Collection
     */
    private function getFakerPhotos(): Collection
    {
        $path = (storage_path($this->fakerPhotosPath));
        return collect(\File::allFiles($path));
    }

    private function deleteAllPhotoInProductsPath()
    {
        $path = ProductPhoto::PRODUCTS_PATH;
        \File::deleteDirectory(storage_path($path), true);
    }

    /**
     * Cria o path responsavel por armazenar as photos dos produtos
     * @param Product $product
     */
    private function createPhotoDir(Product $product)
    {
        $path = ProductPhoto::photosPath($product->id);
        \File::makeDirectory($path, 0777, true);
    }

    /**
     * Cria 5 fotos por produto
     * @param Product $product
     */
    private function createPhotosModels(Product $product)
    {
        foreach (range(1, 5) as $v) {
            $this->createPhotoModel($product);
        }
    }

    /**
     * @param Product $product
     */
    private function createPhotoModel(Product $product)
    {
        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'file_name' => 'image.jpg'
        ]);
        $this->generatePhoto($photo);
    }

    private function generatePhoto(ProductPhoto $photo)
    {
        $photo->file_name = $this->uploadPhoto($photo->product_id);
        $photo->save();
    }

    private function uploadPhoto($productId): string
    {
        /** @var SplFileInfo $photoFile */
        $photoFile = $this->allFakerPhotos->random();
        $uploadFile = new UploadedFile(
            $photoFile->getRealPath(),
            str_random(16) . '.' . $photoFile->getExtension()
        );
        ProductPhoto::uploadFiles($productId, [$uploadFile]);
        //Upload da Foto
        return $uploadFile->hashName();
    }
}