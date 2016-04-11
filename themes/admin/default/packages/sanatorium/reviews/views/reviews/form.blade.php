@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/reviews::reviews/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="reviews-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.reviews.reviews.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $review->exists ? $review->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($review->exists)
							<li>
								<a href="{{ route('admin.sanatorium.reviews.reviews.delete', $review->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/reviews::reviews/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/reviews::reviews/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('user_id', ' has-error') }}">

									<label for="user_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/reviews::reviews/model.general.user_id_help') }}}"></i>
										{{{ trans('sanatorium/reviews::reviews/model.general.user_id') }}}
									</label>
									
									<?php $users = app('platform.users'); ?>

									<select name="user_id" class="form-control">
									@foreach( $users->all() as $user )
										<option value="{{ $user->id }}">{{ $user->email }}</option>
									@endforeach
									</select>

									<span class="help-block">{{{ Alert::onForm('user_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('reviewable_type', ' has-error') }}">

									<label for="reviewable_type" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_type_help') }}}"></i>
										{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_type') }}}
									</label>

									<select name="reviewable_type" class="form-control">
										<option value="Sanatorium\Shop\Models\Product">Product</option>
									</select>
									
									{{--
									<input type="text" class="form-control" name="reviewable_type" id="reviewable_type" placeholder="{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_type') }}}" value="{{{ input()->old('reviewable_type', $review->reviewable_type) }}}">
									--}}

									<span class="help-block">{{{ Alert::onForm('reviewable_type') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('reviewable_id', ' has-error') }}">

									<label for="reviewable_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_id_help') }}}"></i>
										{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_id') }}}
									</label>
									<select name="reviewable_id" class="form-control">							
									@foreach( Sanatorium\Shop\Models\Product::all() as $product )
										<option value="{{ $product->id }}">{{ $product->product_title }}</option>
									@endforeach
									</select>

									{{--
									<input type="text" class="form-control" name="reviewable_id" id="reviewable_id" placeholder="{{{ trans('sanatorium/reviews::reviews/model.general.reviewable_id') }}}" value="{{{ input()->old('reviewable_id', $review->reviewable_id) }}}">
									--}}
									<span class="help-block">{{{ Alert::onForm('reviewable_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('percent', ' has-error') }}">

									<label for="percent" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/reviews::reviews/model.general.percent_help') }}}"></i>
										{{{ trans('sanatorium/reviews::reviews/model.general.percent') }}}
									</label>

									<input type="text" class="form-control" name="percent" id="percent" placeholder="{{{ trans('sanatorium/reviews::reviews/model.general.percent') }}}" value="{{{ input()->old('percent', $review->percent) }}}">

									<span class="help-block">{{{ Alert::onForm('percent') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('text', ' has-error') }}">

									<label for="text" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/reviews::reviews/model.general.text_help') }}}"></i>
										{{{ trans('sanatorium/reviews::reviews/model.general.text') }}}
									</label>

									<textarea class="form-control" name="text" id="text" placeholder="{{{ trans('sanatorium/reviews::reviews/model.general.text') }}}">{{{ input()->old('text', $review->text) }}}</textarea>

									<span class="help-block">{{{ Alert::onForm('text') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($review)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
