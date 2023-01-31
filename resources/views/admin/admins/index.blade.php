<x-admin-layout>
    <x-slot name="header">
        管理者一覧
    </x-slot>

    <div class="text-right">
        <a href="{{ route('admin.register') }}" class="text-blue-500">新規登録</a>
    </div>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <form class="mb-8 text-right" method="get" action="{{ route('admin.admins.index') }}">
            <x-search-form search="{{ $search }}"></x-search-form>
        </form>

        <table class="table-auto w-full text-left whitespace-no-wrap">
            <thead>
                <tr>
                    <x-th>Id</x-th>
                    <x-th>氏名</x-th>
                    <x-th>メールアドレス</x-th>
                    <x-th>登録日</x-th>
                </tr>
            </thead>

            <tbody>
                @foreach ($admins as $admin)
                <tr>
                    <x-td>{{ $admin->id }}</x-td>
                    <x-td>{{ $admin->name }}</x-td>
                    <x-td>{{ $admin->email }}</x-td>
                    <x-td>{{ $admin->created_at->format('Y/m/d') }}</x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $admins->links() }}
    </div>
</x-admin-layout>