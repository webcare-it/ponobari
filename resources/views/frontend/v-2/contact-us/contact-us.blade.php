@extends('frontend.v-2.master')

@section('title')
    Contact Us
@endsection

@section('content-v2')
    <section class="contact-section-wrapper pt-5 pb-5">
      <div class="privacy-policy-heading-wrapper">
          <div class="section-heading-outer">
              <h4 class="section-heading-inner">
                  Contact Us
              </h4>
          </div>
      </div>
      <div class="container">
          <div class="row">
              <div class="col-md-4">
                  <div class="contact-info-item wow fadeInLeftBig">
                      <div class="contact-info-icon">
                          <i class="fas fa-phone-alt"></i>
                      </div>
                      <h5 class="title">
                          Phone
                      </h5>
                      <a href="tel:{{$setting->phone}}">{{$setting->phone}}</a>
                  </div>
              </div>
              @if(!empty($setting->whatsapp))
              <div class="col-md-4">
                  <div class="contact-info-item wow flipInX">
                      <div class="contact-info-icon">
                          <i class="fab fa-whatsapp"></i>
                      </div>
                      <h5 class="title">
                          WhatsApp
                      </h5>
                      <a href="https://wa.me/{{$setting->whatsapp}}" target="_blank">{{$setting->whatsapp}}</a>
                  </div>
              </div>
              @endif
              <div class="col-md-4">
                  <div class="contact-info-item wow fadeInRightBig">
                      <div class="contact-info-icon">
                          <i class="fas fa-envelope"></i>
                      </div>
                      <h5 class="title">
                          Email
                      </h5>
                      <a href="mailto:{{$setting->email}}">{{$setting->email}}</a>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="contact-info-item wow fadeInRightBig">
                      <div class="contact-info-icon">
                          <i class="fas fa-map-marker-alt"></i>
                      </div>
                      <h5 class="title">
                          Address
                      </h5>
                      <p>
                          {!! $setting->address !!}
                      </p>
                  </div>
              </div>
          </div>
          <div class="row mt-5">
              <div class="col-md-6">
                  <div class="contact-form-wrapper">
                      <h4 class="title">
                          Send Message
                      </h4>
                      <form action="{{ route('contact.store') }}" method="post">
                          @csrf
                          <div class="row">
                              <div class="col-md-6">
                                  <label for="name">
                                      Name
                                  </label>
                                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter your name">
                                  @error('name')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                              <div class="col-md-6">
                                  <label for="phone">
                                      Phone
                                  </label>
                                  <input type="text" name="phone" class="form-control  @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Enter phone number">
                                  @error('phone')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                              <div class="col-md-12">
                                  <label for="email">
                                      Email
                                  </label>
                                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter your email">
                                  @error('email')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                              <div class="col-md-12">
                                  <label for="subject">
                                      Subject
                                  </label>
                                  <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Enter subject">
                                  @error('subject')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                              <div class="col-md-12">
                                  <label for="message">
                                      Message
                                  </label>
                                  <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="Enter your message">{{ old('message') }}</textarea>
                                  @error('message')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                              <div class="col-md-12 mt-3">
                                  <button type="submit" class="btn btn-primary">
                                      Send Message
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="contact-map-wrapper">
                      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.916323640314!2d90.38328631498204!3d23.78401498457189!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c718e9909337%3A0x5a384dc2b674044!2sDhaka!5e0!3m2!1sen!2sbd!4v1620000000000!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                  </div>
              </div>
          </div>
      </div>
    </section>
@endsection