<div style="background:#f8fafc;padding:12px 16px;border-radius:6px;margin:12px 0;font-family: Arial, Helvetica, sans-serif;">
    {!! $slot !!}
</div>
<table class="panel" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-content">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="panel-item">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
</table>

