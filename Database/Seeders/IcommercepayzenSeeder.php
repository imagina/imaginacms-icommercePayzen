<?php

namespace Modules\Icommercepayzen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Factory;

class IcommercepayzenSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
//    Model::unguard();

    if (!is_module_enabled('Icommercepayzen')) {
      $this->command->alert("This module: Icommercepayzen is DISABLED!! , please enable the module and then run the seed");
      exit();
    }

    //Validation if the module has been installed before
    $name = config('asgard.icommercepayzen.config.paymentName');
    $paymentMethod = app('Modules\Icommercepayzen\Services\PayzenService')->getPaymentMethod();
    $PaymentMethodRepository = app('Modules\Icommerce\Repositories\PaymentMethodRepository');


    if (!$paymentMethod) {

      $options['init'] = "Modules\Icommercepayzen\Http\Controllers\Api\IcommercePayzenApiController";

      $options['mode'] = "test";
      $options['minimunAmount'] = 0;
      $options['maximumAmount'] = null;
      $options['showInCurrencies'] = ["COP"];

      $options['siteId'] = null;
      $options['signatureKey'] = null;

      $title = 'icommercepayzen::icommercepayzens.single';
      $description = 'icommercepayzen::icommercepayzens.description';

      $params = array(
        'name' => $name,
        'status' => 1,
        'options' => $options,
        'es' => ['title' => trans($title, [], 'es'), 'description' => trans($description, [], 'es')],
        'en' => ['title' => trans($title, [], 'en'), 'description' => trans($description, [], 'en')]
      );

      $paymentMethodCreated = $PaymentMethodRepository->create($params);

    } else {
      if ($paymentMethod->description != trans('icommercepayzen::icommercepayzens.iaDescription', [], locale())) {
        $data = array(
          'es' => ['description' => trans('icommercepayzen::icommercepayzens.iaDescription', [], 'es')],
          'en' => ['description' => trans('icommercepayzen::icommercepayzens.iaDescription', [], 'en')]
        );
        $paymentMethod = $PaymentMethodRepository->update($paymentMethod, $data);
        //Instance file service
        $fileService = app("Modules\Media\Services\FileService");
        //Instance the file path
        $filePath = 'Modules/Icommercepayzen/Resources/img/payzen_default.png';
        if (Storage::disk('local')->exists($filePath)) {
          // Obtener el contenido del archivo
          $fileContents = Storage::disk('local')->get($filePath);
          // Convertir el archivo a base64
          $base64File = base64_encode($fileContents);
          //Get base64 file
          $uploadedFile = getUploadedFileFromBase64($base64File);
          //Create file
          $file = $fileService->store($uploadedFile, 0, 'publicmedia');
          //set file if
          $fileId = $file->id;
          //Sync file id
          $paymentMethod->files()->attach($fileId, ['zone' => 'mainimage']);
        }
      }
//      $this->command->alert("This method has already been installed !!");
    }
  }
}