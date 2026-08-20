@extends('errors::minimal')

@section('title', 'Sessão expirada')
@section('code', '419')
@section('message', 'Sua sessão expirou por segurança. Faça login novamente para continuar.')
@section('action_label', 'Entrar novamente')
@section('action_url', route('login'))
@section('icon_class', 'bi-shield-lock')
