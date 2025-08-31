<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProductController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    protected $db;

    use ResponseTrait;


    public function index()
    {
        // // ini dengan react 
        $model = new ProductModel();
        // Get the ID (can come from the request)
        $id_apotek = $this->request->getVar('id') ?? 24; // Default to 24 if 'id' is not provided in the request

        // // Get pagination parameters
        // $page = $this->request->getVar('page') ?? 1;  // Default to page 1 if not provided
        // $perPage = $this->request->getVar('perPage') ?? 20; // Default to 20 items per page

        // // Get the search query if provided
        // $search = $this->request->getVar('search'); // Can be null if no search term is provided

        // // Get the query builder instance
        // $builder = $model
        //     ->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
        //     ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')  // Join with tsatuanbarang
        //     ->where('tstok.id_apotek', $id)
        //     ->groupBy('tstok.kodeobat')
        //     ->orderBy('tstok.namaobat', 'ASC');  // Default ordering by namaobat descending

        // // Apply the search filter if provided
        // if ($search) {
        //     $builder
        //         ->like('tstok.kodeobat', $search) // Search for items where the name contains the search term
        //         ->orlike('tstok.namaobat', $search);  // Search for items where the name contains the search term
        // }

        // // Get the paginated results
        // $items = $builder->paginate($perPage, 'tstok', $page);

        // // Get total items count (for pagination)
        // $totalItems = $builder->countAllResults(false);  // Count without limiting results
        // $totalPages = ceil($totalItems / $perPage);  // Calculate total pages

        // // Return the response as JSON
        // return $this->respond([
        //     'data' => $items,
        //     'pagination' => [
        //         'total_items' => $totalItems,
        //         'total_pages' => $totalPages,
        //         'current_page' => $page,
        //         'per_page' => $perPage
        //     ]
        // ]);


        // ini dengan delphi 
        // $id_apotek = 24;
        // $model = new MStok();

        // $page = $this->request->getVar('page') ?? 1;
        // $perPage = 10; // Jumlah item per halaman

        // // Ambil data produk dengan pagination
        // $products = $model
        //     ->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
        //     ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')  // Join with tsatuanbarang
        //     ->where('tstok.id_apotek', $id_apotek)
        //     ->groupBy('tstok.kodeobat')
        //     ->orderBy('tstok.namaobat', 'ASC')
        //     ->paginate($perPage, 'default', $page);

        // // Ambil informasi pagination
        // $pager = service('pager');
        // $pagination = [
        //     'current_page' => $page,
        //     'total_pages' => ceil($model->countAll() / $perPage),
        //     'total_items' => $model->countAll(),
        // ];

        // // Kembalikan response dalam format JSON
        // return $this->respond([
        //     'data' => $products,
        //     'pagination' => $pagination,
        // ]);
        
        $results = $model
            ->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tstok.hargabelippn, tsatuanbarang.satuanbarang')
            ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')  // Join with tsatuanbarang
            ->where('tstok.id_apotek', $id_apotek)
            ->groupBy('tstok.kodeobat')
            ->orderBy('tstok.namaobat', 'ASC')
            ->findAll();  // Default ordering by namaobat descending

        return $this->respond(['products' =>$results]);

    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {

        $id_apotek = 24;
        $model = new ProductModel();


        $results = $model->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, MAX(hargabelippn) as hargabeli, SUM(stok) as stok, tstok.satuan, MIN(kadaluarsa) as kadaluarsa, tstok.pemasok, tsatuanbarang.satuanbarang, tobat.kategori, tobat.stokminimal, tobat.zataktiv, tobat.pabrik, tobat.bentuksediaan, tobat.statusbarang')
            ->join('tobat', 'tobat.kodeobat = tstok.kodeobat')
            ->join('tsatuanbarang', 'tsatuanbarang.ID = tstok.satuan')
            ->where('tstok.kodeobat', $id)
            ->where('tstok.id_apotek', $id_apotek)
            ->groupBy('tstok.kodeobat')
            ->orderBy('tstok.namaobat', 'ASC')
            ->findAll();

        return $this->respond(['item' => $results]);

    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */

    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {

        // $id = session()->get('id');
        // $member = session()->get('email'); //id member
        $member = 8; //id member
        $kodebarcode = $this->request->getVar('kodeobat');
        $namaproduk = $this->request->getVar('namaobat');
        $kategori = $this->request->getVar('kategori');
        $satuan = $this->request->getVar('satuan');
        $hargabelippn = str_replace(',', '', $this->request->getVar('hargabeli'));

        $filegambarproduk = $this->request->getFile('gambar');

        // $stokminimal = str_replace(',', '', $this->request->getVar('stokminimal'));
        $stokminimal = ($this->request->getVar('stokminimal') == "") ? 0 : $this->request->getVar('stokminimal');
        $statusbarang = $this->request->getVar('statusbarang');
        $pemasok = $this->request->getVar('pemasok');
        $kadaluarsa = $this->request->getVar('kadaluarsa');
        $zataktiv = $this->request->getVar('zataktif');
        $bentuksediaan = $this->request->getVar('bentuksediaan');
        $kekuatan = $this->request->getVar('kekuatan');
        $pabrik = $this->request->getVar('pabrik');

        $ProductModel = new ProductModel();

        $cekKode = $ProductModel->where('kodeobat', $kodebarcode)->where('member', $member)->first();
        if ($cekKode) {
            return $this->respond([
                'status' => 'error',
                'errors' => 'Kode barcode / produk sudah ada!'
            ], 400);
        }

        $validation = \Config\Services::validation();

        $doValid = $this->validate([
            'kodeobat' => [
                'label' => 'kode barcode / produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                    'is_unique' => '{field} sudah ada, coba yang lain',
                ]
            ],
            'namaobat' => [
                'label' => 'nama produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            'satuan' => [
                'label' => 'satuan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            'kategori' => [
                'label' => 'kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            'pemasok' => [
                'label' => 'pemasok',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            'hargabeli' => [
                'label' => 'harga beli',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            // 'stokminimal' => [
            //     'label' => 'stok minimal',
            //     'rules' => 'required',
            //     'errors' => [
            //         'required' => '{field} wajib diisi!',
            //     ]
            // ],
            'statusbarang' => [
                'label' => 'status barang',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi!',
                ]
            ],
            'kadaluarsa' => [
                'label' => 'kadaluarsa',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} belum dipilih',
                ]
            ],
            'bentuksediaan' => [
                'label' => 'bentuk sediaan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} belum dipilih',
                ]
            ],
            // 'gambar' => [
            //     'label' => 'gambar produk',
            //     'rules' => 'mime_in[gambar,image/png,image/jpg,image/jpeg,image/gif]|is_image[gambar]',
            //     'errors' => [
            //         'mime_in' => 'format {field} tidak sesuai',
            //         'is_image' => '{field} tidak sesuai',
            //     ]
            // ]
        ]);

        // uploaded[gambarproduk]|

        if (!$doValid) {
            // return $this->failValidationErrors($validation->getErrors());
            return $this->respond([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ], 400);
        } else {

            $hargabeli = $hargabelippn / 1.1;
            $filegambarproduk = $this->request->getFile('gambar');
            $kodepath = time();
            if ($filegambarproduk) {
                $namafilegambar = "$kodepath-$kodebarcode-$namaproduk";
                $filegambar = $this->request->getFile('gambar');
                $filegambar->move('assets/upload', $namafilegambar . '.' . $filegambar->getExtension());

                $pathgambar = 'assets/upload/' . $filegambar->getName();
            } else {
                $pathgambar = 'assets/upload/default_product.png';
            }

            $Data = [
                'kodeobat' => $kodebarcode,
                'namaobat' => $namaproduk,
                'satuan' => $satuan,
                'kategori' => $kategori,
                'bentuksediaan' => $bentuksediaan,
                'zataktiv' => $zataktiv,
                'kekuatan' => $kekuatan,
                'pabrik' => $pabrik,
                'stokminimal' => $stokminimal,
                'statusbarang' => $statusbarang,
                'gambar' => $pathgambar,
                // 'gambar' => 'assets/upload/default_product.png',
                'member' => 8,
            ];

            try {
                $save = $ProductModel->save($Data);

                if ($save) {
                    $modelStok = new ProductModel();
                    $modelStok->save([
                        'kodeobat' => $kodebarcode,
                        'namaobat' => $namaproduk,
                        'tgl_transaksi' => date('Y-m-d'),
                        'no_batch' => '-',
                        'kadaluarsa' => $kadaluarsa,
                        'hargabeli' => $hargabeli,
                        'hargabelippn' => $hargabelippn,
                        'stokawal' => 0,
                        'stok' => 0,
                        'satuan' => $satuan,
                        'pemasok' => $pemasok,
                        'ketopname' => 1,
                        'member' => 8,
                        'gambar' => $pathgambar,
                        'id_apotek' => 24

                    ]);

                }
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }

        return $this->respondCreated(['message' => 'Produk berhasil ditambahkan']);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    // public function edit($id = null)
    // {
    //    //
    // }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    // public function update($id = null)
    // {
    //     $id_apotek = 24;
    //     $member = 8; //id member
    //     $kodebarcode = $this->request->getVar('kodeobat');
    //     $kodebarcodelama = $this->request->getVar('kodeobatlama');
    //     $namaproduk = $this->request->getVar('namaobat');
    //     $satuan = $this->request->getVar('satuan');
    //     $kategori = $this->request->getVar('kategori');
    //     $hargabelippn = str_replace(',', '', $this->request->getVar('hargabeli'));

    //     // var_dump($kodebarcode);
    //     // die;
    //     // ppn
    //     //  $tblppn = $this->db->table('tppn');
    //     //  $queryppn = $tblppn->select('*')->get();
    //     //  $rowppn = $queryppn->getrowarray();
    //     //  $ppnkonversi = $rowppn['ppn_konversi'];
    //     $filegambarproduk = $this->request->getFile('gambar');

    //     $stokminimal = str_replace(',', '', $this->request->getVar('stokminimal'));
    //     $statusbarang = $this->request->getVar('statusbarang');
    //     $pemasok = $this->request->getVar('pemasok');
    //     $kadaluarsa = $this->request->getVar('kadaluarsa');
    //     $zataktiv = $this->request->getVar('zataktiv');
    //     $bentuksediaan = $this->request->getVar('bentuksediaan');
    //     $kekuatan = $this->request->getVar('kekuatan');
    //     $pabrik = $this->request->getVar('pabrik');
    //     // $ppnkonversi = str_replace(',', '', $this->request->getVar('ppnkonversi'));

    //     // return $this->respond(['barcode' => $statusbarang]);
    //     // die;

    //     $ProductModel = new ProductModel();
    //     $mStok = new ProductModel();

    //     $validation = \Config\Services::validation();

    //     $doValid = $this->validate([
    //         'kodeobat' => [
    //             'label' => 'kode produk / barcode',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //                 'is_unique' => '{field} sudah ada, coba yang lain',
    //             ]
    //         ],
    //         'namaobat' => [
    //             'label' => 'nama produk',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         'satuan' => [
    //             'label' => 'satuan',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         'kategori' => [
    //             'label' => 'kategori',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         'pemasok' => [
    //             'label' => 'pemasok',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         'hargabeli' => [
    //             'label' => 'harga beli',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         // 'stokminimal' => [
    //         //     'label' => 'stok minimal',
    //         //     'rules' => 'required',
    //         //     'errors' => [
    //         //         'required' => '{field} wajib diisi!',
    //         //     ]
    //         // ],
    //         'statusbarang' => [
    //             'label' => 'status barang',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} wajib diisi!',
    //             ]
    //         ],
    //         'kadaluarsa' => [
    //             'label' => 'kadaluarsa',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} belum dipilih',
    //             ]
    //         ],
    //         'bentuksediaan' => [
    //             'label' => 'bentuk sediaan',
    //             'rules' => 'required',
    //             'errors' => [
    //                 'required' => '{field} belum dipilih',
    //             ]
    //         ],
    //         // 'gambar' => [
    //         //     'label' => 'gambar produk',
    //         //     'rules' => 'mime_in[gambar,image/png,image/jpg,image/jpeg,image/gif]|is_image[gambar]',
    //         //     'errors' => [
    //         //         'mime_in' => 'format {field} tidak sesuai',
    //         //         'is_image' => '{field} tidak sesuai',
    //         //     ]
    //         // ]
    //     ]);

    //     // uploaded[gambarproduk]|

    //     if (!$doValid) {
    //         // return $this->failValidationErrors($validation->getErrors());
    //         return $this->respond([
    //             'status' => 'error',
    //             'errors' => $this->validator->getErrors()
    //         ], 400);
    //     } else {

    //         $hargabeli = $hargabelippn / 1.1;
    //         $filegambarproduk = $this->request->getFile('gambar');
    //         $kodepath = time();

    //         $cekdata = $mStok->where('kodeobat', $id)->where('id_apotek', $id_apotek)->first();
    //         $gambarlama = $cekdata['gambar'];

    //         if ($filegambarproduk) {
    //             if ($gambarlama != 'assets/upload/default_product.png') {
    //                 unlink($gambarlama);
    //                 $namafilegambar = "$kodepath-$kodebarcode-$namaproduk";
    //                 $filegambar = $this->request->getFile('gambar');
    //                 $filegambar->move('assets/upload', $namafilegambar . '.' . $filegambar->getExtension());

    //                 $pathgambar = 'assets/upload/' . $filegambar->getName();

    //             } else {
    //                 $namafilegambar = "$kodepath-$kodebarcode-$namaproduk";
    //                 $filegambar = $this->request->getFile('gambar');
    //                 $filegambar->move('assets/upload', $namafilegambar . '.' . $filegambar->getExtension());

    //                 $pathgambar = 'assets/upload/' . $filegambar->getName();
    //             }
    //         } else {
    //             $pathgambar = $gambarlama;
    //         }

    //         $Data = [
    //             'kodeobat' => $kodebarcode,
    //             'namaobat' => $namaproduk,
    //             'satuan' => $satuan,
    //             'kategori' => $kategori,
    //             'zataktiv' => $zataktiv,
    //             'bentuksediaan' => $bentuksediaan,
    //             'kekuatan' => $kekuatan,
    //             'pabrik' => $pabrik,
    //             'stokminimal' => $stokminimal,
    //             'statusbarang' => $statusbarang,
    //             'gambar' => $pathgambar,
    //             'member' => $member,
    //         ];

    //         try {
    //             // update produk 
    //             $ProductModel
    //                 ->where('kodeobat', $kodebarcodelama)
    //                 ->where('member', $member)
    //                 ->set($Data)
    //                 ->update();

    //             // update kemasan 
    //             $UnitModel = new UnitModel();
    //             $updatekemasan = ['kodebarang' => $kodebarcode];
    //             $UnitModel->where('kodebarang', $kodebarcodelama)
    //                 ->where('member', $member)
    //                 ->set($updatekemasan)
    //                 ->update();

    //             // update stok
    //             $updatestok = [
    //                 'kodeobat' => $kodebarcode,
    //                 'namaobat' => $namaproduk,
    //                 'satuan' => $satuan,
    //                 'hargabelippn' => $hargabelippn,
    //                 'kadaluarsa' => $kadaluarsa,
    //                 'gambar' => $pathgambar,
    //                 'pemasok' => $pemasok
    //             ];
    //             $mStok
    //                 ->where('kodeobat', $kodebarcodelama)
    //                 ->where('member', $member)
    //                 ->set($updatestok)
    //                 ->update();

    //             // update kartu stok 
    //             $mKartustok = new MKartustok();
    //             $uKartustok = ['kodeobat' => $kodebarcode];
    //             $mKartustok->where('kodeobat', $kodebarcodelama)
    //                 ->where('member', $member)
    //                 ->set($uKartustok)
    //                 ->update();

    //         } catch (\Exception $e) {
    //             exit($e->getMessage());
    //         }
    //     }

    //     return $this->respond(['message' => 'Produk berhasil diubah']);
    // }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    // public function delete($id = null)
    // {
    //     $id_apotek = 24;
    //     $member = 8;
    //     $model = new MStok();
    //     $modelP = new ProductModel();
    //     $modelK = new MKartustok();
    //     $product = $model->where('kodeobat', $id)->where('id_apotek', $id_apotek)->first();
    //     $delProduct = $model->where('kodeobat', $id)->where('id_apotek', $id_apotek)->delete();

    //     if (!$product) {
    //         return $this->failNotFound('Product not found');
    //     }

    //     if ($delProduct) {
    //         $modelP->where('kodeobat', $id)->where('member', $member)->delete();
    //         $modelK->where('kodeobat', $id)->where('id_apotek', $id_apotek)->delete();

    //         return $this->respond(['success' => true, 'message' => 'Produk berhasil dihapus']);
    //     } else {
    //         return $this->failServerError('Failed to delete product');
    //     }
    // }

    public function kemasan($id_produk = null)
    {
        $id =24;
        $model = new ProductModel();

        $results = $model
                    ->select('tstok.kodeobat, tstok.namaobat, tstok.gambar, tstok.id_apotek, SUM(stok) as stok, tsatuanbarang.satuanbarang')
                    ->join('tkemasan', 'tkemasan.kodebarang = tstok.kodeobat')
                    ->join('tsatuanbarang', 'tsatuanbarang.ID = tkemasan.kemasan')
                    ->where('tstok.kodeobat', $id_produk)
                    ->where('tstok.id_apotek', $id)
                    ->groupBy('tkemasan.kemasan')
                    ->findAll();

        return $this->respond(['data' => $results]);
    }
}