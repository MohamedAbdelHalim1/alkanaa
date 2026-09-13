@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="kn-page-head">
        <h1 class="kn-page-title">{{ translate('Support Ticket') }}</h1>
        <!-- Create a Ticket -->
        <button type="button" class="kn-btn kn-btn-primary" data-toggle="modal" data-target="#ticket_modal">
            <i class="las la-plus" aria-hidden="true"></i>
            <span>{{ translate('Create a Ticket') }}</span>
        </button>
    </div>

    <!-- Tickets -->
    <section class="kn-panel">
        <div class="kn-panel-head">
            <h2 class="kn-panel-title">{{ translate('Tickets') }}</h2>
        </div>
        <div class="kn-panel-body is-flush">
            <div class="kn-table-scroll">
                <table class="table aiz-table">
                    <thead>
                        <tr>
                            <th>{{ translate('Ticket ID') }}</th>
                            <th>{{ translate('Sending Date') }}</th>
                            <th>{{ translate('Subject') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th class="kn-td-end">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $key => $ticket)
                            <tr>
                                <td class="fw-700"><bdi>#{{ $ticket->code }}</bdi></td>
                                <td>{{ date('Y.m.d h:i:m', strtotime($ticket->created_at)) }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>
                                    @if ($ticket->status == 'pending')
                                        <span class="kn-status is-danger">{{ translate('Pending') }}</span>
                                    @elseif ($ticket->status == 'open')
                                        <span class="kn-status is-info">{{ translate('Open') }}</span>
                                    @else
                                        <span class="kn-status is-success">{{ translate('Solved') }}</span>
                                    @endif
                                </td>
                                <td class="kn-td-end">
                                    <a href="{{ route('support_ticket.show', encrypt($ticket->id)) }}" class="kn-text-btn">
                                        {{ translate('View Details') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $tickets->links() }}
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Ticket modal -->
    <div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="ticket_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticket_modal_title">{{ translate('Create a Ticket') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="" action="{{ route('support_ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="kn-field">
                            <label for="ticket-subject">{{ translate('Subject') }}</label>
                            <input type="text" id="ticket-subject" class="form-control" placeholder="{{ translate('Subject') }}" name="subject" required>
                        </div>

                        <div class="kn-field">
                            <label for="ticket-details">{{ translate('Provide a detailed description') }}</label>
                            <textarea type="text" id="ticket-details" class="form-control" rows="3" name="details" placeholder="{{ translate('Type your reply') }}" data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo" required></textarea>
                        </div>
                        <div class="kn-field">
                            <span class="kn-label">{{ translate('Photo') }}</span>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="attachments" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                        <div class="kn-form-actions" style="gap: 8px;">
                            <button type="button" class="kn-btn kn-btn-outline" data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="kn-btn kn-btn-primary">{{ translate('Send Ticket') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
