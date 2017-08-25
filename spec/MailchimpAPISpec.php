<?php

namespace spec\MailChimp;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class MailChimpAPISpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('ea400f0d078e0ddddf638e95e69f9b0f-us10');
    }

    function it_is_initializable() {
        $this->shouldHaveType('MailChimp\MailChimpAPI');
    }

    function it_should_change_datacenter() {
        $this->getEndpoint()->shouldReturn('https://us10.api.mailchimp.com/3.0/');
    }

    function it_should_return_an_object() {

        $this->request('lists', [
            'fields' => 'lists.id,lists.name,lists.stats.member_count',
            'count'  => 10
        ])->shouldReturnAnInstanceOf('stdClass');
    }

}
