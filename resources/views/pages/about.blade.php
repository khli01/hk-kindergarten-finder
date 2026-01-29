@extends('layouts.app')

@section('title', __('messages.about') . ' - ' . __('messages.app_name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">{{ __('messages.about') }}</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    @if(app()->getLocale() === 'zh-TW')
                        <h4>我們的使命</h4>
                        <p>香港幼稚園搜尋致力於幫助家長為孩子找到最合適的幼稚園。我們提供全面的學校資訊、排名、升小成功率以及重要的報名日期，讓家長能夠做出明智的選擇。</p>
                        
                        <h4>我們提供的服務</h4>
                        <ul>
                            <li>涵蓋香港18區的幼稚園資料庫</li>
                            <li>學校排名及升小成功率數據</li>
                            <li>報名截止日期追蹤和提醒</li>
                            <li>PN班及K1-K3班級資訊</li>
                            <li>學校特色及設施介紹</li>
                            <li>直接連結至學校官方網站</li>
                        </ul>
                        
                        <h4>您的意見很重要</h4>
                        <p>作為家長，您的經驗和見解對其他家長非常寶貴。我們鼓勵您分享您的意見和建議，幫助我們不斷改進服務，為所有家長提供更好的資源。</p>
                    @elseif(app()->getLocale() === 'zh-CN')
                        <h4>我们的使命</h4>
                        <p>香港幼儿园搜索致力于帮助家长为孩子找到最合适的幼儿园。我们提供全面的学校信息、排名、升小成功率以及重要的报名日期，让家长能够做出明智的选择。</p>
                        
                        <h4>我们提供的服务</h4>
                        <ul>
                            <li>涵盖香港18区的幼儿园数据库</li>
                            <li>学校排名及升小成功率数据</li>
                            <li>报名截止日期追踪和提醒</li>
                            <li>PN班及K1-K3班级信息</li>
                            <li>学校特色及设施介绍</li>
                            <li>直接链接至学校官方网站</li>
                        </ul>
                        
                        <h4>您的意见很重要</h4>
                        <p>作为家长，您的经验和见解对其他家长非常宝贵。我们鼓励您分享您的意见和建议，帮助我们不断改进服务，为所有家长提供更好的资源。</p>
                    @else
                        <h4>Our Mission</h4>
                        <p>HK Kindergarten Finder is dedicated to helping parents find the best kindergarten for their children. We provide comprehensive school information, rankings, primary school success rates, and important registration deadlines to help parents make informed decisions.</p>
                        
                        <h4>What We Offer</h4>
                        <ul>
                            <li>Database covering all 18 districts of Hong Kong</li>
                            <li>School rankings and primary school success rate data</li>
                            <li>Registration deadline tracking and reminders</li>
                            <li>PN class and K1-K3 information</li>
                            <li>School features and facilities</li>
                            <li>Direct links to school official websites</li>
                        </ul>
                        
                        <h4>Your Feedback Matters</h4>
                        <p>As a parent, your experiences and insights are invaluable to other parents. We encourage you to share your feedback and suggestions to help us continuously improve our service and provide better resources for all parents.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
