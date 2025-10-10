<x-mail::message>
# 取引完了のお知らせ

以下の商品の取引が完了し、評価が送信されました。

- 商品名： **{{ $transaction->item->name }}**
- 購入者： **{{ $transaction->buyer->name }}**

<x-mail::button :url="route('transactions.show', $transaction->id) . '!prompt=rate'">
取引内容を確認し、購入者の評価を行ってください。
</x-mail::button>

ありがとうございました。<br>
{{ config('app.name') }}
</x-mail::message>
