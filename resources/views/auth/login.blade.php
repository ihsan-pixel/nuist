@extends('layouts.master-without-nav')

@section('title')
Login - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY
@endsection

@section('css')
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background-color: #ebe5ff;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  width: 100%;
  max-width: 1000px;
}

.login-box {
  display: flex;
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Left Section */
.left {
  flex: 1;
  padding: 50px;
}

.left h2 {
  font-weight: 600;
  margin-bottom: 8px;
}

.left p {
  font-size: 14px;
  color: #777;
  margin-bottom: 30px;
}

.input-group {
  margin-bottom: 20px;
}

.input-group input {
  width: 100%;
  padding: 12px 16px;
  border-radius: 10px;
  border: 1px solid #ddd;
  background: #f4f0ff;
  outline: none;
}

.login-btn {
  width: 100%;
  background: #7f5af0;
  color: white;
  border: none;
  padding: 12px 0;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 500;
  box-shadow: 0 4px 10px rgba(127,90,240,0.3);
  transition: 0.3s;
}

.login-btn:hover {
  background: #6a40d8;
}

.divider {
  text-align: center;
  margin: 25px 0;
  color: #666;
  font-size: 14px;
}

.social-login button {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 10px;
  background: white;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
}

.social-login img {
  width: 20px;
  height: 20px;
}

/* Right Section */
.right {
  flex: 1;
  background: linear-gradient(135deg, #7f5af0, #9c88ff);
  display: flex;
  justify-content: center;
  align-items: center;
}

.right img {
  width: 70%;
  max-width: 350px;
  border-radius: 20px;
}

/* Footer */
.footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 30px;
  color: #333;
  font-size: 14px;
}

.footer .profile {
  display: flex;
  align-items: center;
  gap: 10px;
}

.footer .profile img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.footer span {
  color: #7f5af0;
  font-size: 13px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .login-box {
    flex-direction: column;
  }
  .left, .right {
    flex: none;
  }
  .right {
    background: none;
    padding: 20px;
  }
  .right img {
    width: 50%;
    max-width: 200px;
  }
  .footer {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}
@endsection

@section('body')
<body>
@endsection

@section('content')
<div class="container">
  <div class="login-box">
    <div class="left">
      <h2>LOGIN</h2>
      <p>How to i get started lorem ipsum dolor at?</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group">
          <input type="email" name="email" placeholder="Username" value="{{ old('email') }}" autocomplete="email" autofocus>
          @error('email')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <div class="input-group">
          <input type="password" name="password" placeholder="Password">
          @error('password')
          <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <button class="login-btn" type="submit">Login Now</button>

        <div class="divider">Login with Others</div>

        <div class="social-login">
          <button class="google-btn">
            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google">
            Login with Google
          </button>
          <button class="facebook-btn">
            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/facebook/facebook-original.svg" alt="Facebook">
            Login with Facebook
          </button>
        </div>
      </form>
    </div>

    <div class="right">
      <img src="https://i.ibb.co/GJt8RxY/woman-tablet.png" alt="Illustration">
    </div>
  </div>

  <div class="footer">
    <div class="profile">
      <img src="https://i.ibb.co/X25zPnG/avatar.jpg" alt="avatar">
      <div>
        <strong>Mohammed Jawed</strong><br>
        <span>@thisuix571</span>
      </div>
    </div>
    <div class="email">
      thisuix571@gmail.com
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection

