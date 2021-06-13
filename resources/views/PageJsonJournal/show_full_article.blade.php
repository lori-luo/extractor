<x-app-layout>
    @section('title',$title)
    <div class="mt-2">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="table-responsive">
                    <main class="container">
                        <div class="row g-5">
                            <div class="col-md-8">
                                <article class="blog-post">
                                    <h2 class="blog-post-title mt-3">
                                        {{ $journal->title }}
                                    </h2>
                                    <p class="blog-post-meta">
                                        Created date: {{ date('F-d-Y', strtotime($journal->created_date))  }}
                                        <br>
                                        Last updated: {{ date('F-d-Y', strtotime($journal->last_updated))  }}
                                    </p>

                                    <hr>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">General Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>EISSN</th>
                                                <td>
                                                    @isset($journal->eissn)
                                                    {{ $journal->eissn }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>PISSN</th>
                                                <td>
                                                    @isset($journal->pissn)
                                                    {{ $journal->pissn }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Language</th>
                                                <td>
                                                    @if($journal->language_obj())
                                                    @foreach($journal->language_obj() as $language)
                                                    @if($loop->last)
                                                    {{ $language }}
                                                    @else
                                                    {{ $language }},
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Editorial</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Review Process</th>
                                                <td>
                                                    @if($journal->editorial_obj()->review_process)
                                                    @foreach($journal->editorial_obj()->review_process as $process)
                                                    @if($loop->last)
                                                    {{ $process }}
                                                    @else
                                                    {{ $process }},
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Review URL</th>
                                                <td>
                                                    @isset($journal->editorial_obj()->review_url)
                                                    <a href="{{ $journal->editorial_obj()->review_url }}" target="_blank">
                                                        {{ $journal->editorial_obj()->review_url }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Board URL</th>
                                                <td>
                                                    @isset($journal->editorial_obj()->board_url)
                                                    <a href="{{ $journal->editorial_obj()->board_url }}" target="_blank">
                                                        {{ $journal->editorial_obj()->board_url }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>




                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">PID Scheme</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($journal->pid_scheme_obj()->scheme)
                                            <tr>
                                                <th>Scheme</th>
                                                <td>

                                                    @foreach($journal->pid_scheme_obj()->scheme as $scheme)
                                                    @if($loop->last)
                                                    {{ $scheme }}
                                                    @else
                                                    {{ $scheme }},
                                                    @endif
                                                    @endforeach

                                                </td>
                                            </tr>
                                            @endisset
                                            @isset($journal->pid_scheme_obj()->has_pid_scheme)
                                            <tr>
                                                <th>Has PID Scheme</th>
                                                <td>

                                                    {{ $journal->pid_scheme_obj()->has_pid_scheme ? 'Yes' : 'No' }}

                                                </td>
                                            </tr>
                                            @endisset
                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Copyright</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($journal->copyright_obj()->author_retains)
                                            <tr>
                                                <th>Author Retains</th>
                                                <td>

                                                    {{ $journal->copyright_obj()->author_retains ? 'Yes':'No' }}

                                                </td>
                                            </tr>
                                            @endisset
                                            <tr>
                                                <th>URL</th>
                                                <td>
                                                    @isset($journal->copyright_obj()->url)
                                                    <a href="{{ $journal->copyright_obj()->url }}" target="_blank">
                                                        {{ $journal->copyright_obj()->url }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Plagiarism</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($journal->plagiarism_obj()->detection)
                                            <tr>
                                                <th>Detection</th>
                                                <td>

                                                    {{ $journal->plagiarism_obj()->detection ?'Yes':'No' }}

                                                </td>
                                            </tr>
                                            @endisset
                                            <tr>
                                                <th>URL</th>
                                                <td>
                                                    @isset($journal->plagiarism_obj()->url)
                                                    <a href="{{ $journal->plagiarism_obj()->url }}" target="_blank">
                                                        {{ $journal->plagiarism_obj()->url }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>




                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Article</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>License Display</th>
                                                <td>
                                                    @isset($journal->article_obj()->license_display)
                                                    @foreach($journal->article_obj()->license_display as $display)
                                                    @if($loop->last)
                                                    {{ $display }}
                                                    @else
                                                    {{ $display }},
                                                    @endif
                                                    @endforeach
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>License Display Example URL</th>
                                                <td>
                                                    @isset($journal->article_obj()->license_display_example_url)
                                                    <a href="{{ $journal->article_obj()->license_display_example_url }}" target="_blank">
                                                        {{ $journal->article_obj()->license_display_example_url }}
                                                    </a>
                                                    @endisset

                                                </td>
                                            </tr>
                                            @isset($journal->article_obj()->orcid)
                                            <tr>
                                                <th>ORCID</th>
                                                <td>

                                                    {{ $journal->article_obj()->orcid ? 'Yes' : 'No' }}

                                                </td>
                                            </tr>
                                            @endisset
                                            @isset($journal->article_obj()->i4oc_open_citations)
                                            <tr>
                                                <th>i4oc Open Citations</th>
                                                <td>

                                                    {{ ($journal->article_obj()->i4oc_open_citations ? 'Yes' : 'No') }}

                                                </td>
                                            </tr>
                                            @endisset
                                        </tbody>
                                    </table>



                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Institution</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td>
                                                    @isset($journal->institution_obj()->name)
                                                    {{ $journal->institution_obj()->name }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Country</th>
                                                <td>
                                                    @isset($journal->institution_obj()->country)
                                                    {{ $journal->institution_obj()->country }}
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Publisher</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td>
                                                    @isset($journal->publisher_obj()->name)
                                                    {{ $journal->publisher_obj()->name }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Country</th>
                                                <td>
                                                    @isset($journal->publisher_obj()->country)
                                                    {{ $journal->publisher_obj()->country }}
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Other Chargers</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Has Other Charges</th>
                                                <td>
                                                    @isset($journal->other_charges_obj()->has_other_charges)
                                                    {{ $journal->other_charges_obj()->has_other_charges ? 'Yes' : 'No' }}

                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>URL</th>
                                                <td>
                                                    @isset($journal->other_charges_obj()->url)
                                                    <a href="{{ $journal->other_charges_obj()->url }}" target="_blank">
                                                        {{ $journal->other_charges_obj()->url }}
                                                    </a>

                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>






                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Preservation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @isset($journal->preservation_obj()->has_preservation)
                                            <tr>
                                                <th>Has Preservation</th>
                                                <td>

                                                    {{ $journal->preservation_obj()->has_preservation  ? 'Yes' : 'No' }}

                                                </td>
                                            </tr>
                                            @endisset
                                            @isset($journal->preservation_obj()->service)
                                            <tr>
                                                <th>Service</th>
                                                <td>

                                                    @foreach($journal->preservation_obj()->service as $service)
                                                    @if($loop->last)
                                                    {{ $service }}
                                                    @else
                                                    {{ $service }},
                                                    @endif
                                                    @endforeach


                                                </td>
                                            </tr>
                                            @endisset
                                            @isset($journal->preservation_obj()->national_library)
                                            <tr>
                                                <th>National Library</th>
                                                <td>

                                                    @foreach($journal->preservation_obj()->national_library as $library)
                                                    @if($loop->last)
                                                    {{ $library }}
                                                    @else
                                                    {{ $library }},
                                                    @endif
                                                    @endforeach


                                                </td>
                                            </tr>
                                            @endisset
                                            @isset($journal->preservation_obj()->url)
                                            <tr>
                                                <th>URL</th>
                                                <td>

                                                    <a href="{{ $journal->preservation_obj()->url }}" target="_blank">
                                                        {{ $journal->preservation_obj()->url }}
                                                    </a>

                                                </td>
                                            </tr>
                                            @endisset
                                        </tbody>
                                    </table>




                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="6">License</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr>
                                                <th scope="col">NC</th>
                                                <th scope="col">ND</th>
                                                <th scope="col">BY</th>
                                                <th scope="col">type</th>
                                                <th scope="col">SA</th>
                                                <th scope="col">url</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($journal->license_obj() as $license)
                                            <tr>
                                                <td>
                                                    {{ $license->NC }}
                                                </td>
                                                <td>
                                                    {{ $license->ND }}
                                                </td>
                                                <td>
                                                    {{ $license->BY }}
                                                </td>
                                                <td>
                                                    {{ $license->type }}
                                                </td>
                                                <td>
                                                    {{ $license->SA }}
                                                </td>
                                                <td>
                                                    @isset($license->url)
                                                    <a href="{{ $license->url }}" target="_blank">
                                                        {{ $license->url }}
                                                    </a>
                                                    @endisset

                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>



                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">Ref</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                            <tr>
                                                <th>Aims Scope</th>
                                                <td>
                                                    @isset($journal->ref_obj()->aims_scope)
                                                    <a href="{{ $journal->ref_obj()->aims_scope }}" target="_blank">
                                                        {{ $journal->ref_obj()->aims_scope }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Journal</th>
                                                <td>
                                                    @isset($journal->ref_obj()->journal)
                                                    <a href="{{ $journal->ref_obj()->journal }}" target="_blank">
                                                        {{ $journal->ref_obj()->journal }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>OA Statement</th>
                                                <td>
                                                    @isset($journal->ref_obj()->oa_statement)
                                                    <a href="{{ $journal->ref_obj()->oa_statement }}" target="_blank">
                                                        {{ $journal->ref_obj()->oa_statement }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Author Instructions</th>
                                                <td>
                                                    @isset($journal->ref_obj()->author_instructions)
                                                    <a href="{{ $journal->ref_obj()->author_instructions }}" target="_blank">
                                                        {{ $journal->ref_obj()->author_instructions }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>License Terms</th>
                                                <td>
                                                    @isset($journal->ref_obj()->license_terms)
                                                    <a href="{{ $journal->ref_obj()->license_terms }}" target="_blank">
                                                        {{ $journal->ref_obj()->license_terms }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="table-primary">
                                                <th scope="col" colspan="2">APC</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                            <tr>
                                                <th>Has APC</th>
                                                <td>
                                                    @isset($journal->apc_obj()->has_apc)
                                                    {{ $journal->apc_obj()->has_apc ? 'Yes':'No' }}
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>URL</th>
                                                <td>
                                                    @isset($journal->apc_obj()->url)
                                                    <a href="{{ $journal->apc_obj()->url }}" target="_blank">
                                                        {{ $journal->apc_obj()->url }}
                                                    </a>
                                                    @endisset
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Max</th>
                                                <td>
                                                    @isset($journal->apc_obj()->max)
                                                    @foreach($journal->apc_obj()->max as $m)
                                                    @if($loop->last)
                                                    Price : {{ $m->price }} , Currency: {{ $m->currency }}
                                                    @else
                                                    Price : {{ $m->price }} , Currency: {{ $m->currency }} <br>
                                                    @endif
                                                    @endforeach


                                                    @endisset
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                </article>




                            </div>

                            <div class="col-md-4">
                                <div class="position-sticky" style="top: 2rem;">

                                    <div class="p-4">
                                        <h4 class="fst-italic">Subjects</h4>
                                        <ol class="list-unstyled mb-0">
                                            @if($journal->subject_obj())
                                            @foreach($journal->subject_obj() as $subject)
                                            <li><a href="#">{{ $subject->term }}</a></li>
                                            @endforeach
                                            @endif
                                        </ol>
                                    </div>

                                    <div class="p-4">
                                        <h4 class="fst-italic">Keywords</h4>
                                        <ol class="list-unstyled">
                                            @if($journal->keyword_obj())
                                            @foreach($journal->keyword_obj() as $keyword)
                                            <li><a href="#">{{ $keyword }}</a></li>
                                            @endforeach
                                            @endif
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </main>


                </div>

            </div>
        </div>
    </div>
</x-app-layout>