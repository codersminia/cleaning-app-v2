<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;
    public $url;

    public function __construct(Proposal $proposal, $url)
    {
        $this->proposal = $proposal;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Proposal for Review: ' . $this->proposal->proposal_name)
                    ->html("
                        <h2>Proposal Shared by Believe 313 Staffing</h2>
                        <p>Thank you for the opportunity to earn your business.</p>
                        <p><strong>Proposal Name:</strong> {$this->proposal->proposal_name}</p>
                        <p>Click the link below to view and sign the proposal:</p>
                        <br>
                        <a href='{$this->url}' style='background-color:#17a2b8;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Click Here to View & Sign</a>
                        <br><br>
                        <p>Or copy this link: {$this->url}</p>
                    ");
    }
}