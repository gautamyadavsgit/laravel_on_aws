<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\PropertyModel;
use App\Models\PropertyDocumentModel;
use App\Models\PropertyImageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class PropertyController extends Controller
{
    public function property()
    {


        $property = DB::table('properties')
            ->leftJoin('properties_document', 'properties.id', '=', 'properties_document.property_id')
            ->leftJoin('property_images', 'properties.id', '=', 'property_images.property_id')
            ->select('properties.*', 'properties_document.document_1', 'property_images.property_images')
            ->get();

        $data['property'] = $property;
        return view('admin.properties.property')->with($data);
    }

    public function add_property()
    {
        return view('admin.properties.add_property');
    }

    // public function store_property(Request $request)
    // {
    // //     $image = $request->file('image');
    // //    dd($image);
    // //     $data = $request->all();
    // //    dd($data);

    // // if ($request->hasFile('image')) {
    // //     $file = $request->file('image');
    // //     $fileName = time() . '_' . $file->getClientOriginalName();
    // //     $file->storeAs('property_documents', $fileName);
    // //     dd($file);
    // // } else {
    // //     dd('No file uploaded.');
    // // }

    //     $property = new PropertyModel;
    //     $property->availability = $request->availability;
    //     $property->name        = $request->name;
    //     $property->address1    = $request->address1;
    //     $property->address2    = $request->address2;
    //     $property->zoning      = $request->zoning;
    //     $property->value       = $request->value;
    //     $property->amenities   = $request->amenities;
    //     $property->description = $request->description;
    //     $property->market_title       = $request->market_title;
    //     $property->market_description = $request->market_description;
    //     $property->market_file        = $request->market_file;
    //     $property->floorplan_1        = $request->floorplan_1;
    //     $property->floorplan_2        = $request->floorplan_2;
    //     $property->floorplan_3        = $request->floorplan_3;
    //     $property->deed_fractions_1   = $request->deed_fractions_1;
    //     $property->deed_fractions_2   = $request->deed_fractions_2;
    //     $property->annual_appreciation= $request->annual_appreciation;
    //     $property->aum_fee_1          = $request->aum_fee_1;
    //     $property->aum_fee_2          = $request->aum_fee_2;
    //     $property->aum_fee_3          = $request->aum_fee_3;
    //     $property->save();

    //     $propertyId = $property->id;

    //     $property_tax = new PropertyTaxModel;
    //     $property_tax->property_id = $propertyId; 
    //     $property_tax->Tax = $request->availability;
    //     $property_tax->save();

    //    // $propertyDocs = [];

        
    //         //$propertyId = PropertyModel::latest()->first()->id;

           
    //         $propertyDocs = [];

    //         // Process each uploaded file
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $key => $file) {
    //                 $fileName = time() . '_' . $file->getClientOriginalName();
    //                 $file->storeAs('property_documents', $fileName);

                   
    //                 $propertyDocs[$key]['property_document'] = $fileName;
    //                 $propertyDocs[$key]['document_1'] = $fileName;
    //             }
    //         }

    //         // Store $propertyDocs in your database
    //         foreach ($propertyDocs as $propertyDoc) {
    //             $propertyDocument = new PropertyDocumentModel();
    //             $propertyDocument->property_id = $propertyId;
    //             $propertyDocument->property_document = $propertyDoc['property_document'];
    //             $propertyDocument->document_1 = $propertyDoc['document_1'];
               
    //             $propertyDocument->save();
    //         }



    //     // $propertyDoc = new PropertyDocumentModel();
    //     // $propertyDoc->property_id = $propertyId;
       
    //     // if ($request->hasFile('image')) {
    //     //     $file = $request->file('image');
    //     //     $fileName = time() . '_' . $file->getClientOriginalName();
    //     //     $file->storeAs('property_documents', $fileName);
    //     //     $propertyDoc->property_document = $fileName;
    //     // }

    //     // $propertyDoc->save();

    //     return redirect('property');
      
        
    // }

    public function store_property(Request $request)
    {
       

        $property = new PropertyModel;
        $property->availability = $request->availability;
        $property->name        = $request->name;
        $property->address1    = $request->address1;
        $property->address2    = $request->address2;
        $property->zoning      = $request->zoning;
        $property->value       = $request->value;
        $property->amenities   = $request->amenities;
        $property->description = $request->description;
        $property->market_title       = $request->market_title;
        $property->market_description = $request->market_description;
        $property->market_file        = $request->market_file;
        $property->floorplan_1        = $request->floorplan_1;
        $property->floorplan_2        = $request->floorplan_2;
        $property->floorplan_3        = $request->floorplan_3;
        $property->deed_fractions_1   = $request->deed_fractions_1;
        $property->deed_fractions_2   = $request->deed_fractions_2;
        $property->annual_appreciation= $request->annual_appreciation;
        $property->aum_fee_1          = $request->aum_fee_1;
        $property->aum_fee_2          = $request->aum_fee_2;
        $property->aum_fee_3          = $request->aum_fee_3;
        $property->save();

        $propertyId = $property->id;

        // Save property tax
        $property_tax = new PropertyTaxModel;
        $property_tax->property_id = $propertyId; 
        $property_tax->Tax = $request->availability; 
        $property_tax->save();

        // Save property image
        // $property_img = new PropertyImageModel;
        // $property_img->property_id = $propertyId;
        // $property_img->property_images = $request->availability;
        // $property_img->save();

        // Save single image in database
        // if($request->hasFile('images')) {

        //     $file = $request->file('images');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     //$file->storeAs('public/property_documents', $fileName);
        //     $file->move('property_documents', $fileName);

        //     $propertyDocument = new PropertyDocumentModel();
        //     $propertyDocument->property_id = $propertyId;
        //     $propertyDocument->property_document = $fileName;

        //     $propertyDocument->save();
        // }

        
       

               
        // Process each uploaded file
        // if($request->hasFile('images')) {

        //     // Store each images in folder
        //     foreach ($request->file('images') as $key => $file) {
        //         $fileName = time() . '_' . $file->getClientOriginalName();
        //        // $file->storeAs('property_documents', $fileName);
        //         $file->move('property_documents', $fileName);
              
        //         $propertyDocs[$key]['property_document_1'] = $fileName;
        //         $propertyDocs[$key]['property_document_2'] = $fileName;
        //     }
            
        //     // Store $propertyDocs in your database
        //     foreach ($propertyDocs as $propertyDoc) {
        //         $propertyDocument = new PropertyDocumentModel();
        //         $propertyDocument->property_id = $propertyId;
        //         $propertyDocument->property_document_1 = $propertyDoc['property_document_1'];
        //         $propertyDocument->property_document_2 = $propertyDoc['property_document_2'];

        //         $propertyDocument->save();
        //     }
        // }

        

        if ($request->hasFile('images')) {
            $propertyDocument = PropertyDocumentModel::where('property_id', $propertyId)->first();
        
            if (!$propertyDocument) {
                $propertyDocument = new PropertyDocumentModel();
                $propertyDocument->property_id = $propertyId;
            }
        
            $imageCount = 1;
            foreach ($request->file('images') as $key => $file) {
               
                $columnName = "property_document_" . $imageCount;
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move('property_documents', $fileName);
        
                $propertyDocument->$columnName = $fileName;
                $imageCount++;
            }
        
            $propertyDocument->save();
        }
        

        return redirect('property');
    }


    public function edit_property(Request $request, $id)
    {

        $property = DB::table('property')
            ->leftJoin('property_tax', 'property.id', '=', 'property_tax.property_id')
            ->leftJoin('property_document', 'property.id', '=', 'property_document.property_id')
            ->leftJoin('property_images', 'property.id', '=', 'property_images.property_id')
            ->select('property.*', 'property_tax.Tax', 'property_document.property_document_1', 'property_images.property_images')
            ->where('property.id', $id)
            ->first();

        //$post = PropertyModel::find($id);
        // $data['post'] = $property;

        $data['property'] = $property;
        // return view('admin.properties.edit_property', compact('property'));
        return view('admin.properties.edit_property')->with($data);

    }

    public function update_property(Request $request, $id)
    {

        $post = PropertyModel::find($id);
        $post->availability = $request->availability;
        $post->name               = $request->name;
        $post->address1           = $request->address1;
        $post->address2           = $request->address2;
        $post->zoning             = $request->zoning;
        $post->value              = $request->value;
        $post->amenities          = $request->amenities;
        $post->description        = $request->description;
        $post->market_title       = $request->market_title;
        $post->market_description = $request->market_description;
        $post->market_file        = $request->market_file;
        $post->floorplan_1        = $request->floorplan_1;
        $post->floorplan_2        = $request->floorplan_2;
        $post->floorplan_3        = $request->floorplan_3;
        $post->deed_fractions_1   = $request->deed_fractions_1;
        $post->deed_fractions_2   = $request->deed_fractions_2;
        $post->annual_appreciation= $request->annual_appreciation;
        $post->aum_fee_1          = $request->aum_fee_1;
        $post->aum_fee_2          = $request->aum_fee_2;
        $post->aum_fee_3          = $request->aum_fee_3;
        $post->update(); 
       
        // Update property tax
        $property_tax = PropertyTaxModel::where('property_id', $id)->first();
        $property_tax->Tax = $request->availability; 
        $property_tax->update();

        // Update property image
        // $property_img = PropertyImageModel::find($id);
        // $property_img->property_images = $request->availability;
        // $property_img->update();

        // Update PropertyDocumentModel
        // $propertyDocument = PropertyDocumentModel::where('property_id', $id)->first();

        // if ($request->hasFile('images')) {
        //     //$image_path = asset("property_documents/{$propertyDocument->property_document}");
        //     $image_path = public_path("property_documents/{$propertyDocument->property_document}");
       
        //     if (File::exists($image_path)) {
        //         File::delete($image_path);
        //     }

        //     $file = $request->file('images');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move('property_documents', $fileName);

        //     if ($propertyDocument) {
        //         $propertyDocument->property_document = $fileName;
        //         $propertyDocument->update();
        //     } else {
        //         PropertyDocumentModel::create([
        //             'property_id' => $id,
        //             'property_document' => $fileName,
        //         ]);
        //     }
        // }


       
        
        // Process each uploaded file

        // if($request->hasFile('images')) {

        //     foreach ($request->file('images') as $key => $file) {

        //     $propertyDocument = PropertyDocumentModel::where('property_id', $id)->first();
            
        //     $image_path = public_path("property_documents/{$propertyDocument->property_document}");

        //     // echo "<pre>";
        //     // print_r($image_path);
        //     // echo "</pre>";
       
        //     if (File::exists($image_path)) {
        //         File::delete($image_path);
        //     }

        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move('property_documents', $fileName);

        //     $propertyDocs[$key]['property_document_1'] = $fileName;
        //     $propertyDocs[$key]['property_document_2'] = $fileName;
        //     }
        //    // dd('ok');

        //     // Store $propertyDocs in your database
        //     foreach ($propertyDocs as $propertyDoc) {
        //         $propertyDocument = PropertyDocumentModel::find($id);
        //         $propertyDocument->property_document = $propertyDoc['property_document_1'];
        //         $propertyDocument->document_1 = $propertyDoc['property_document_2'];

        //         $propertyDocument->update();
        //     }
        // }



        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $key => $file) {
        //         $propertyDocument = PropertyDocumentModel::where('property_id', $id)->get();

        //         foreach($propertyDocument as $propertyDoc){
        //                 echo "<pre>";
        //                 print_r($propertyDoc);
        //                 echo "<pre>";
        //             // Delete existing files
        //             // $existingFilePath = public_path("property_documents/{$propertyDoc->property_document}");
        //             // if (File::exists($existingFilePath)) {
        //             //     File::delete($existingFilePath);
        //             // }
            
        //             // // Move the new file
        //             // $fileName = time() . '_' . $file->getClientOriginalName();
        //             // $file->move('property_documents', $fileName);
        //         }
               
        //         dd('ok');
        //         // Update or create the record in the database
        //         if ($propertyDocument) {
        //             $propertyDocument->property_document = $fileName;
        //             $propertyDocument->document_1 = $fileName;
        //             $propertyDocument->update();
        //         } else {
        //             PropertyDocumentModel::create([
        //                 'property_id' => $id,
        //                 'property_document' => $fileName,
        //                 'document_1' => $fileName,
        //             ]);
        //         }
        //     }
        // }

        // $imageCount = 1;
        // foreach ($request->file('images') as $key => $file) {
           
        //     $columnName = "property_document_" . $imageCount;
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move('property_documents', $fileName);
    
        //     $propertyDocument->$columnName = $fileName;
        //     $imageCount++;
        // }
    
        // $imageCount = 1;
        // foreach ($request->file('images') as $key => $file) {
           
        //     $columnName = "property_document_" . $imageCount;
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $file->move('property_documents', $fileName);
    
        //     $propertyDocument->$columnName = $fileName;
        //     $imageCount++;
        // }



        // if ($request->hasFile('images')) {

        //     $propertyDocument = PropertyDocumentModel::where('property_id', $id)->first();

        //     if ($propertyDocument) {
        //         $attributes = $propertyDocument->getAttributes();

        //         foreach ($attributes as $columnName => $value) {
        //             // Check if the column name matches the pattern you are interested in (e.g., property_document_)
        //             if (strpos($columnName, 'property_document_') === 0) {
        //                 // Delete existing files
        //                 $existingFilePath = public_path("property_documents/{$value}");
                        
        //                 if (File::exists($existingFilePath)) {
        //                     File::delete($existingFilePath);
        //                 }
        //             }
        //         }
        //     }



        //     // $propertyDocument = PropertyDocumentModel::where('property_id', $id)->first();
            
        //     // $propertyDocument->property_document_1;
        //     // $propertyDocument->property_document_2;
        //     // while($propertyDocument){
        //     //     dd('okkk');
        //     //     // Delete existing files
        //     //     $existingFilePath = public_path("property_documents/{$propertyDocument->property_document_1}");
        //     //     if (File::exists($existingFilePath)) {
        //     //         File::delete($existingFilePath);
        //     //     }
        //     //     dd('ok');
        //     // }
         
        //     $imageCount = 1;
        //     foreach ($request->file('images') as $key => $file) {
               
        //         $columnName = "property_document_" . $imageCount;
        //         $fileName = time() . '_' . $file->getClientOriginalName();
        //         $file->move('property_documents', $fileName);
        
        //         $propertyDocument->$columnName = $fileName;
        //         $imageCount++;
        //     }
        
        //     $propertyDocument->update();
        // }


        // if ($request->hasFile('images')) {
        //     dd($request->all());
        //     $propertyDocument = PropertyDocumentModel::where('property_id', $id)->first();
        
        //     if (!$propertyDocument) {
        //         $propertyDocument = new PropertyDocumentModel();
        //         $propertyDocument->property_id = $id;
        //     }
        
        //     $imageCount = 1;
        //     foreach ($request->file('images') as $key => $file) {
               
        //         $columnName = "property_document_" . $imageCount;
        //         $fileName = time() . '_' . $file->getClientOriginalName();
        //         $file->move('property_documents', $fileName);
        
        //         $propertyDocument->$columnName = $fileName;
        //         $imageCount++;
        //     }
        
        //     $propertyDocument->update();
        // }
        

        return redirect('property');
    }

    public function delete_property($id)
    {
        $property = PropertyModel::find($id);
        if ($property) {
            $property->delete();
        }

        $propertyTax = PropertyTaxModel::where('property_id', $id)->first();
        if ($propertyTax) {
            $propertyTax->delete();
        }

        // $propertyImg = PropertyImageModel::where('property_id', $id)->first();
        // if ($propertyImg) {
        //     $propertyImg->delete();
        // }

        $propertyDoc = PropertyDocumentModel::where('property_id', $id)->first();
        if ($propertyDoc) {
            $propertyDoc->delete();
        }
        return redirect()->back();
    }

  
}
