<h2>رسالة من صفحة الاتصال</h2>

<p><strong>الاسم:</strong> {{ $data['name'] }}</p>
<p><strong>البريد الإلكتروني:</strong> {{ $data['email'] }}</p>
@if(!empty($data['phone']))
<p><strong>الهاتف:</strong> {{ $data['phone'] }}</p>
@endif
@if(!empty($data['company']))
<p><strong>الشركة:</strong> {{ $data['company'] }}</p>
@endif
@if(!empty($data['service']))
<p><strong>الخدمة المطلوبة:</strong> {{ $data['service'] }}</p>
@endif
<p><strong>الرسالة:</strong></p>
<p>{{ $data['message'] }}</p>
