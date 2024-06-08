<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
     /**
      *  @OA\Post(
      *      path="/api/v1/user/item/list",
      *      tags={"Item"},
      *      summary="Item List",
      *      operationId="itemList",
      *      description="ItemList",
      *      security={{"bearerAuth":{}}},
      *      @OA\Parameter(
      *          name="start",
      *          required=false,
      *          in="query",
      *          example="0",
      *          description="no of record you already get",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *      @OA\Parameter(
      *          name="limit",
      *          required=false,
      *          in="query",
      *          example="10",
      *          description="no of record you want to get",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *     @OA\Parameter(
      *          name="search",
      *          required=false,
      *          in="query",
      *          example="",
      *          description="search by title",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *      @OA\Parameter(
      *          name="category_id",
      *          required=false,
      *          in="query",
      *          example="",
      *          description="Category Id",
      *          @OA\Schema(
      *          type="number",
      *         ),
      *       ),
      *      @OA\Parameter(
      *          name="sort_by",
      *          required=false,
      *          in="query",
      *          example="",
      *          description="Sort by column(title)",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *      @OA\Parameter(
      *          name="sort_order",
      *          required=false,
      *          in="query",
      *          example="",
      *          description="Sort order (asc or desc)",
      *          @OA\Schema(
      *          type="string",
      *         ),
      *       ),
      *      @OA\Response(
      *         response=200,
      *         description="json schema",
      *         @OA\MediaType(
      *             mediaType="application/json",
      *         ),
      *     ),
      *     @OA\Response(
      *         response=404,
      *         description="Invalid Request"
      *     ),
      * )
      */

     public function list(Request $request)
     {
          try {
               $start = @$request->start ? $request->start : 0;
               $limit = @$request->limit ? $request->limit : 10;
               $search = @$request->search ? $request->search : '';
               $category_id = @$request->category_id ? $request->category_id : '';
               $sort_by = @$request->sort_by ? $request->sort_by : 'id';
               $sort_order = @$request->sort_order ? $request->sort_order : 'desc';

               $data = [
                    'search' => $search,
                    'category_id' => $category_id
               ];

               $query = Item::getQueryForList($data);

               $total_count = Item::select('id')
                    ->where(function ($query) use ($data) {
                         if (@$data['category_id']) {
                              $query->where('category_id', $data['category_id']);
                         }
                    })
                    ->count();

               $filter_count = $query->clone()->count();

               $itemList = $query->clone()
                    ->orderBy($sort_by, $sort_order)
                    ->skip($start)
                    ->limit($limit)
                    ->get();

               if(!empty($itemList) && count($itemList) > 0){
                    foreach ($itemList as $item){
                         if(!empty($item->image)){
                              $item->image = asset('image/' . $item->image);
                         }
                         if(!empty($item->pdf)){
                              $item->pdf = asset('pdf/' . $item->pdf);
                         }
                         if(!empty($item->excel)){
                              $item->excel = asset('excel/' . $item->excel);
                         }
                    }
               }

               $data = [
                    'status_code' => 200,
                    'message' => 'Record get successfully.',
                    'data' => [
                         'total_count' => $total_count,
                         'filter_count' => $filter_count,
                         'itemList' => $itemList,
                    ]
               ];

               return sendJsonResponse($data);
          } catch (\Exception $e) {
               Log::error([
                    'method' => __METHOD__,
                    'error' => ['file' => $e->getFile(), 'line' => $e->getLine(), 'message' => $e->getMessage()],
                    'created_at' => date("Y-m-d H:i:s")
               ]);
               return sendJsonResponse(array('status_code' => 500, 'message' => 'Something went wrong.'));
          }
     }
}
