<x-admin-layout>
    <x-slot name="header">
        会員一覧
    </x-slot>

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
        <form class="mb-8 text-right" method="get" action="{{ route('admin.users.index') }}">
            <x-search-form></x-search-form>
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
                @foreach ($users as $user)
                <tr>
                    <x-td>{{ $user->id }}</x-td>
                    <x-td>{{ $user->name }}</x-td>
                    <x-td>{{ $user->email }}</x-td>
                    <x-td>{{ $user->created_at->format('Y/m/d') }}</x-td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</x-admin-layout>