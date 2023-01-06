<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            新規作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <section class="text-gray-600 body-font relative">
                <form method="post" action="{{ route('admin.products.store')}}" enctype="multipart/form-data">
                    @csrf
                <div class="container px-5 mx-auto">
                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                        <div class="relative">
                            <label for="name" class="leading-7 text-sm text-gray-600">商品名</label>
                            <input type="text" id="name" name="name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <label class="leading-7 text-sm text-gray-600">画像</label><br>
                            <input type="file" name="image">
                        </div>
                        </div>
                        
                        <div class="p-2 w-full">
                        <div class="relative">
                            <label for="introduction" class="leading-7 text-sm text-gray-600">説明文</label>
                            <textarea id="introduction" name="introduction" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <label for="price" class="leading-7 text-sm text-gray-600">税抜き価格</label>
                            <input type="number" id="price" name="price" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <div class="relative">
                            <label class="leading-7 text-sm text-gray-600">ステータス</label><br>
                            <input type="radio" name="status" value="0">販売中
                            <input type="radio" name="status" value="1">販売停止中
                        </div>
                        </div>

                        <div class="p-2 w-full">
                        <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
                </section>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
