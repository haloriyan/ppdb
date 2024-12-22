<table>
    <thead>
        <tr>
            <th style="text-decoration: bold;"><b>Nama</b></th>
            <th style="text-decoration: bold;"><b>No. WhatsApp / Email</b></th>
            @foreach ($tableCols as $col)
                <th style="text-decoration: bold;"><b>{{ $col['label'] }}</b></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <td>
                    {{ $student->name }}
                </td>
                <td>
                    @if (is_numeric($student->phone))
                        <a href="https://wa.me/62{{ $student->phone }}">
                            {{ '=' . '"0' . $student->phone . '"' }}
                        </a>
                    @else
                        <a href="mailto:{{ $student->phone }}">{{ $student->phone }}</a>
                    @endif
                </td>
                @foreach ($tableCols as $col)
                    @php
                        $fields = json_decode(base64_decode($student->fields), false);
                    @endphp
                    @foreach ($fields as $field)
                        @if ($field->key == $col['key'] && $field->type != "FILE")
                            <td>{{ $field->value }}</td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>