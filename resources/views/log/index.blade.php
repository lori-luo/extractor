<x-app-layout>

    <div class="py-12">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="table table-sm">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col" style="width: 15%;">User</th>
                            <th scope="col">Action</th>
                            <th scope="col" style="width: 15%;">When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="width: 15%;">{{ $log->user->name }}</td>
                            <td>
                                @if($log->type=="delete-article")

                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Deleted Article</h4>
                                    <p>
                                        {{ $obj->title  }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        @foreach(json_decode($obj->link_list) as $list)
                                    <p class="mb-0">
                                        @if($loop->last)
                                        <a target="_blank" href="{{ $list->url }}">{{ ($list->content_type ?? '') .'-' . $list->type }}</a>
                                        @else
                                        <a target="_blank" href="{{ $list->url }}">{{ ($list->content_type ?? '') .'-' . $list->type }}</a> |
                                        @endif
                                    </p>
                                    @endforeach

                                    </p>
                                </div>

                                @elseif($log->type=="delete-journal")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-info" role="alert">
                                    <h4 class="alert-heading">Deleted Journal</h4>
                                    <p>
                                        {{ $obj->title  }}
                                    </p>
                                    <hr>


                                    <p class="mb-0">
                                        @php
                                        $ref = json_decode($obj->ref);
                                        @endphp
                                        <a target="_blank" href="{{ $ref->aims_scope }}">aims_scope</a> |
                                        <a target="_blank" href="{{ $ref->journal }}">journal</a> |
                                        <a target="_blank" href="{{ $ref->oa_statement }}">oa_statement</a> |
                                        <a target="_blank" href="{{ $ref->author_instructions }}">author_instructions</a> |
                                        <a target="_blank" href="{{ $ref->license_terms }}">license_terms</a>

                                    </p>
                                </div>

                                @elseif($log->type=="login")
                                <span class="badge bg-success">Login</span>
                                @elseif($log->type=="logout")
                                <span class="badge bg-danger">Logout</span>

                                @elseif($log->type=="import-article")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Article Import</h4>
                                    <p>
                                        {{ $obj->file_name  }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">

                                        Record: {{ $obj->original_record_count }} | Extracted:{{ $obj->extracted_record_count }} | New:{{ $obj->new_record_count }} | Updated:{{ $obj->updated_record_count }}

                                    </p>
                                </div>

                                @elseif($log->type=="import-journal")

                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Journal Import</h4>
                                    <p>
                                        {{ $obj->file_name  }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">

                                        Record: {{ $obj->original_record_count }} | Extracted:{{ $obj->extracted_record_count }} | New:{{ $obj->new_record_count }} | Updated:{{ $obj->updated_record_count }}

                                    </p>
                                </div>

                                @elseif($log->type=="export-article")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Article Export</h4>
                                    <p>
                                        {{ $obj->file_name  }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        <a href="{{ route('log.dl_art_export_file',$obj->file_name) }}">Download File</a>
                                    </p>
                                </div>

                                @elseif($log->type=="export-journal")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Journal Export</h4>
                                    <p>
                                        {{ $obj->file_name  }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        <a href="{{ route('log.dl_art_export_file',$obj->file_name) }}">Download File</a>
                                    </p>
                                </div>

                                @elseif($log->type=="delete-keyword-article")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Article-Delete Keyword</h4>
                                    <p>
                                        {{ $obj->article->title }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Keyword: <span class="badge bg-primary">{{ $obj->keyword  }}</span>
                                    </p>
                                </div>

                                @elseif($log->type=="delete-keyword-journal")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Journal-Delete Keyword</h4>
                                    <p>
                                        {{ $obj->journal->title }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Keyword: <span class="badge bg-primary">{{ $obj->keyword  }}</span>
                                    </p>
                                </div>

                                @elseif($log->type=="delete-subject-article")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Article-Delete Subject</h4>
                                    <p>
                                        {{ $obj->article->title }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Subject: <span class="badge bg-success">{{ $obj->subject  }}</span>
                                    </p>
                                </div>

                                @elseif($log->type=="delete-subject-journal")
                                @php
                                $obj = json_decode($log->obj);
                                @endphp
                                <div class="alert alert-secondary" role="alert">
                                    <h4 class="alert-heading">Journal-Delete Subject</h4>
                                    <p>
                                        {{ $obj->journal->title }}
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Subject: <span class="badge bg-success">{{ $obj->subject  }}</span>
                                    </p>
                                </div>




                                @endif



                            </td>
                            <td style="width: 15%;">

                                {{ $log->created_at->diffForHumans() }}

                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>